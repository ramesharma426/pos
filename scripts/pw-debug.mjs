import { chromium } from 'playwright';

const BASE = process.env.BASE_URL || 'http://localhost:8000';

const consoleErrors = [];
const pageErrors = [];
const failedRequests = [];
const badResponses = [];

const browser = await chromium.launch();
const page = await browser.newPage();

page.on('console', (msg) => {
  if (msg.type() === 'error' || msg.type() === 'warning') {
    consoleErrors.push(`[${msg.type()}] ${msg.text()}`);
  }
});
page.on('pageerror', (err) => pageErrors.push(err.message));
page.on('requestfailed', (req) =>
  failedRequests.push(`${req.method()} ${req.url()} -> ${req.failure()?.errorText}`)
);
page.on('response', (res) => {
  if (res.status() >= 400) badResponses.push(`${res.status()} ${res.request().method()} ${res.url()}`);
});

console.log(`Loading ${BASE}/ ...`);
const resp = await page.goto(`${BASE}/`, { waitUntil: 'networkidle', timeout: 30000 });
console.log(`Main document status: ${resp?.status()}`);

// Give the Vue app a moment to mount / route
await page.waitForTimeout(1500);

const title = await page.title();
const appHtmlLen = (await page.locator('#app').innerHTML().catch(() => '')).length;
const url = page.url();
await page.screenshot({ path: 'scripts/pw-home.png', fullPage: true });

console.log('\n===== PLAYWRIGHT DEBUG REPORT =====');
console.log(`Final URL:       ${url}`);
console.log(`Title:           ${title}`);
console.log(`#app innerHTML:  ${appHtmlLen} chars ${appHtmlLen > 0 ? '(mounted)' : '(EMPTY — app did not mount)'}`);
console.log(`\nPage errors (${pageErrors.length}):`);
pageErrors.forEach((e) => console.log('  ✗ ' + e));
console.log(`\nConsole errors/warnings (${consoleErrors.length}):`);
consoleErrors.slice(0, 30).forEach((e) => console.log('  • ' + e));
console.log(`\nFailed requests (${failedRequests.length}):`);
failedRequests.forEach((e) => console.log('  ✗ ' + e));
console.log(`\nHTTP >=400 responses (${badResponses.length}):`);
badResponses.forEach((e) => console.log('  ✗ ' + e));
console.log('\nScreenshot saved: scripts/pw-home.png');

await browser.close();
