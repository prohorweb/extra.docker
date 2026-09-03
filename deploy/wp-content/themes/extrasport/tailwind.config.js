/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./views/**/*.php',
		'./layouts/**/*.php',
		'./components/**/*.php',
		'./sections/**/*.php',
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
					dark: '#080809',
				},
			},
		},
	},
	plugins: [],
};
