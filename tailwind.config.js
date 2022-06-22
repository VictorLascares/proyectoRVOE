/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
        "./resources/**/*.blade.php", 
        "./resources/**/*.js", 
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/tailwind.blade.php",
        "./node_modules/flowbite/**/*.js"
    ],
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
