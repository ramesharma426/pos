import ThermalPrinterEncoder from "thermal-printer-encoder";

let device;

const setupDevice = (device) => {
    return device
        .open()
        .then(() => device.selectConfiguration(1))
        .then(() => device.claimInterface(0));
};

const print = (orderItems, totalAmount, order) => {

    let encoder = new ThermalPrinterEncoder({language: "esc-pos"});

    let discount = [];
    let subTotal = [];
    if (order.discount > 0) {
        subTotal = [
            "Sub Total",
            "",
            (encoder) => encoder.text(numbersWithCommas(totalAmount)),
        ];
        discount = [
            "Discount",
            "",
            (encoder) => encoder.text(`(${numbersWithCommas(parseFloat(order.discount))})`),
        ];
    }

    let encodedCommand = encoder
        .initialize()
        .codepage("cp437")
        .invert(true)
        .width(2)
        .line("Fusion Café + Lounge Bar")
        .height(1)
        .invert(false)
        .align("center")
        .width(1)
        .size("small")
        .text(`Date: ${order.bill_date}`)
        .align("right")
        .size("normal")
        .newline()
        .text(
            order.customer_name !== "-" ? `Customer:${order.customer_name}  ` : ""
        )
        .align("left")
        .newline()
        .text(`Bill No:${order.bill_number}  `)
        .text(`Table No:${order.table_number}`)
        .align("left")
        .newline()
        .size("small")
        .line("-".repeat(63))
        .size("normal")
        .text("Items                         Qty        Amount")
        .newline()
        .size("small")
        .line("-".repeat(63))
        .table(
            [
                {width: 40, align: "left"},
                {width: 10, align: "left"},
                {width: 13, align: "right"},
            ],
            [
                ...orderItems.map((orderItem) => [
                    (encoder) => encoder.size("small").text(orderItem.product_variant),
                    (encoder) => encoder.size("small").text(`${orderItem.quantity}`),
                    (encoder) =>
                        encoder
                            .size("small")
                            .text(
                                `${numbersWithCommas(orderItem.quantity * orderItem.rate)}`
                            ),
                ]),
                ["-".repeat(40), "-".repeat(10), "-".repeat(13)],
                subTotal,
                discount,
                [
                    "Total",
                    "",
                    (encoder) =>
                        encoder.text(
                            `Rs. ${numbersWithCommas(totalAmount - order.discount)}`
                        ),
                ],
                ["-".repeat(40), "-".repeat(10), "-".repeat(13)],
            ]
        )
        .align("center")
        .line("Goods once sold will not be taken back.")
        .italic(true)
        .size("small")
        .text("** Thank You **")
        .italic(false)
        .newline()
        .newline()
        .newline()
        .newline()
        .newline()
        .cut("partial")
        .encode();

    // [Name] Set buzzer
    // [Format] ASCII ESC B n t
    // Hex 1B 42 n t
    // Decimal 27 66 n t
    // [Description] 1<=n<=9，1<=t<=9
    // [Details] The buzzer ring when print the order.
    // •nRefers to the number of buzzer times
    // •tRefers to the buzzer beeps every few hours (t * 100) milliseconds.
    const beepCommand = new Uint8Array([27, 66, 3, 2]);

    const endpointNumber =
        device.configuration.interfaces[0].alternate.endpoints.find(
            (obj) => obj.direction === "out"
        ).endpointNumber;

    device
        .transferOut(
            endpointNumber,
            new Uint8Array([...encodedCommand, ...beepCommand])
        )
        .catch((error) => {
            console.error(error);
        });
};

const connectAndPrint = (orderItems, totalAmount, order) => {
    if (device == null) {
        navigator.usb
            .requestDevice({filters: [{vendorId: 1046}]})
            .then((selectedDevice) => {
                device = selectedDevice;
                return setupDevice(device);
            })
            .then(_ => print(orderItems, totalAmount, order))
            .catch((error) => {
                console.error("connection", error);
            });
    } else print(orderItems, totalAmount, order);
};


navigator?.usb?.getDevices()
    .then((devices) => {
        if (devices.length > 0) {
            device = devices[0];
            return setupDevice(device);
        }
    })
    .catch((error) => {
        console.error("connection navigator", error);
    });


const numbersWithCommas = (x) =>
    x.toString().split(".")[0].length > 3
        ? x
            .toString()
            .substring(0, x.toString().split(".")[0].length - 3)
            .replace(/\B(?=(\d{2})+(?!\d))/g, ",") +
        "," +
        x.toString().substring(x.toString().split(".")[0].length - 3)
        : x.toString();

export default connectAndPrint;
