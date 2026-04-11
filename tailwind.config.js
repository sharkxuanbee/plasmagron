/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php',
		'./assets/js/**/*.js',
		'./js/**/*.js',
	],
	theme: {
		extend: {
			fontFamily: {
				rajdhani: [ 'Rajdhani', 'Arial Narrow', 'Roboto Condensed', 'sans-serif' ],
				roboto: [ 'Roboto', 'Segoe UI', 'Helvetica Neue', 'Arial', 'sans-serif' ],
			},
		},
	},
	plugins: [],
};
