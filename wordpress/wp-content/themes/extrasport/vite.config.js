import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname( fileURLToPath( import.meta.url ) );

export default defineConfig( {
	plugins: [ tailwindcss() ],
	build: {
		outDir: 'assets/dist',
		emptyOutDir: true,
		rollupOptions: {
			input: {
				main: path.resolve( __dirname, 'assets/src/main.js' ),
				style: path.resolve( __dirname, 'assets/src/input.css' ),
			},
			output: {
				entryFileNames: '[name].js',
				assetFileNames: ( info ) => {
					if ( info.name?.endsWith( '.css' ) ) {
						return 'output.css';
					}
					return '[name][extname]';
				},
			},
		},
	},
} );
