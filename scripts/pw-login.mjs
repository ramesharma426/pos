import { chromium } from 'playwright';

const BASE = process.env.BASE_URL || 'http://localhost:8000';
const EMAIL = process.env.POS_EMAIL || 'admin@pos.com';
const PASS = process.env.POS_PASS || 'password';

const consoleErrors = [];
const badResponses = [];

const browser = await chromium.launch();
const page = await browser.newPage();
page.on('console', (m) => { if (m.type() === 'error') consoleErrors.push(m.text()); });
page.on('response', (r) => { if (r.status() >= 400) badResponses.push(`${r.status()} ${r.request().method()} ${r.url()}`); });

await page.goto(`${BASE}/`, { waitUntil: 'networkidle' });
await page.waitForTimeout(800);
console.log('At:', page.url());

// Fill login form (placeholders Email / Password from the rendered page)
await page.getByPlaceholder('Email').fill(EMAIL);
await page.getByPlaceholder('Password').fill(PASS);
await page.getByRole('button', { name: /sign in/i }).click();

// Wait for navigation away from /login
await page.waitForTimeout(2500);
const afterUrl = page.url();
await page.screenshot({ path: 'scripts/pw-after-login.png', fullPage: true });

console.log('\n===== LOGIN E2E =====');
console.log('URL after login:', afterUrl);
console.log('Left /login page:', !afterUrl.endsWith('/login') ? 'YES ✅' : 'NO ✗ (still on login)');
console.log(`Console errors (${consoleErrors.length}):`);
consoleErrors.slice(0, 20).forEach((e) => console.log('  • ' + e));
console.log(`HTTP >=400 (${badResponses.length}):`);
badResponses.forEach((e) => console.log('  ✗ ' + e));
console.log('Screenshot: scripts/pw-after-login.png');

await browser.close();
