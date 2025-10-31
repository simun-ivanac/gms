/**
 * This is the main entry point for project scripts.
 */

function dynamicImport(paths) {
	paths.keys().forEach(paths);
}

// Register all scripts.
// eslint-disable-next-line
dynamicImport(require.context('./', true, /index\.js$/));
