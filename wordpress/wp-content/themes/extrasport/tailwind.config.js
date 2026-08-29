/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php',
		'./assets/src/**/*.js',
	],
	theme: {
		extend: {
			fontFamily: {
				oswald: [ 'Oswald', 'sans-serif' ],
				roboto: [ 'Roboto', 'sans-serif' ],
			},
			colors: {
				brand: {
					primary: '#e11d48',
					dark: '#141416',
				},
			},
		},
	},
	plugins: [],
};
