/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: [
    './components/**/*.{html,php}',
    './delete/**/*.{html,php}',
    './edit/**/*.{html,php}',
    './tambah/**/*.{html,php}',
    './*.html',
    './*.php',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
}