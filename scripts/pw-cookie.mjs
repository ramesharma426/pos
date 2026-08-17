import { chromium } from 'playwright';

const BASE = process.env.BASE_URL || 'http://localhost:8000';
const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();

await page.goto(`${BASE}/`, { waitUntil: 'networkidle' });
await page.getByPlaceholder('Email').fill('admin@pos.com');
await page.getByPlaceholder('Password').fill('password');
await page.getByRole('button', { name: /sign in/i }).click();
await page.waitForURL(/\/orders/, { timeout: 8000 }).catch(() => {});

const afterLogin = await page.evaluate(() => ({
  cookieUD: (document.cookie.match(/(?:^|; )UD=([^;]*)/) || [])[1] || null,
  localStorageUD: localStorage.getItem('UD'),
}));

// Full reload — persistence must come from the cookie, not the Vuex state
await page.reload({ waitUntil: 'networkidle' });
await page.waitForTimeout(800);
const afterReloadUrl = page.url();

console.log('===== COOKIE AUTH TEST =====');
console.log(`Login lands on:            ${'/orders'} -> ${page.url().endsWith('/orders') || afterReloadUrl.endsWith('/orders') ? 'OK' : 'check'}`);
console.log(`UD cookie set:             ${afterLogin.cookieUD ? 'YES ✅  ' + decodeURIComponent(afterLogin.cookieUD).slice(0, 60) : 'NO ✗'}`);
console.log(`localStorage UD used:      ${afterLogin.localStorageUD ? 'YES ✗ (should be null)' : 'NO ✅ (correct — cookie only)'}`);
console.log(`Still authed after reload: ${!afterReloadUrl.endsWith('/login') ? 'YES ✅ (' + afterReloadUrl.replace(BASE, '') + ')' : 'NO ✗ (redirected to /login)'}`);

await browser.close();
