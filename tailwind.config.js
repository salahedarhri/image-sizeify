/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'selector',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily:{
        lato:['Lato'],
        barlow:['Barlow'],
      },
      colors:{
        darkShade:'#4D869C',
        mediumShade:'#7AB2B2',
        lightShade:'#EEF7FF',
        bgShade:'#CDE8E5',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

