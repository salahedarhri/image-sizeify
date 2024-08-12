/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
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
        darkShade:'#3b414f',
        mediumShade:'#708871',
        lightShade:'#BEC6A0',
        whiteShade:'#FEF3E2',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

