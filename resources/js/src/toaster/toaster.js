import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

const isObject = (value) => {
  if (value == null) {
    return false;
  }
  return typeof value === "object";
};

export const showErrorToast = (error) => {
  const objProps = (object) => {
    for (let value in object) {
      if (isObject(object[value])) {
        objProps(object[value]);
      } else {
        toaster.error(object[value], { position: "top-right", duration: 2250 });
      }
    }
  };
  objProps(error);
};

export const showSuccessToast = (message) => {
  const objProps = function (object) {
    for (let value in object) {
      if (isObject(object[value])) {
        objProps(object[value]);
      } else {
        toaster.success(object[value], { position: "top", duration: 1450 });
      }
    }
  };
  objProps(message);
};
