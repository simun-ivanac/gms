/**
 * This is a main entrypoint for JS rules.
 */

import js from '@eslint/js';
import globals from 'globals';
import { defineConfig } from 'eslint/config';
import stylistic from '@stylistic/eslint-plugin';

export default defineConfig([
	{
		files: [
			'assets/**/*.{js,mjs}',
		],
		linterOptions: {
			reportUnusedDisableDirectives: 'error',
		},
		ignores: [
			'node_modules/',
			'public/',
			'templates/',
			'var/',
			'vendor/',
			"*.config.mjs",
			"*.config.js",
		],
		plugins: {
			js,
			stylistic,
		},
		extends: [
			'js/recommended'
		],
		languageOptions: {
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
			},
			globals: {
				...globals.serviceworker,
				...globals.browser,
			},
		},
		rules: {
			// Possible problems.
			'no-await-in-loop': 'error',
			'no-const-assign': 'error',
			'no-constant-binary-expression': 'error',
			'no-duplicate-case': 'error',
			'no-duplicate-imports': 'error',
			'no-unexpected-multiline': 'error',
			'no-unreachable': 'error',
			'no-use-before-define': 'error',
			'no-useless-assignment': 'error',
			'require-atomic-updates': 'error',
			'valid-typeof': 'error',
			// Suggestions.
			'camelcase': ['error', {
				properties: 'never',
				ignoreImports: true,
			}],
			'capitalized-comments': [
				'error',
				'always',
				{
					ignoreConsecutiveComments: true,
					ignoreInlineComments: true,
				}
			],
			'curly': 'error',
			'default-case-last': 'error',
			'dot-notation': 'error',
			'eqeqeq': 'error',
			'grouped-accessor-pairs': 'error',
			'id-length': 'error',
			'no-alert': 'error',
			'no-console': ['warn', {
				allow: ['warn', 'error'],
			}],
			'no-empty': 'error',
			'no-empty-function': 'error',
			'no-implicit-coercion': 'error',
			'no-lone-blocks': 'error',
			'no-nested-ternary': 'error',
			'no-param-reassign': 'error',
			'no-redeclare': 'error',
			'no-underscore-dangle': ['error', {
				allowAfterThis: true,
			}],
			'no-var': 'error',
			'no-void': 'error',
			'require-await': 'error',
			'yoda': 'error',
			// Deprecated and replaced with new ones.
			'stylistic/array-bracket-newline': ['error', 'always'],
			'stylistic/array-bracket-spacing': ['error', 'never'],
			'stylistic/arrow-spacing': 'error',
			'stylistic/brace-style': 'error',
			'stylistic/comma-dangle': ['error', {
				arrays: 'always',
				objects: 'always',
				imports: 'always',
				exports: 'always',
				functions: 'never',
				importAttributes: 'never',
				dynamicImports: 'never',
				enums: 'never',
				generics: 'never',
				tuples: 'never',
			}],
			'stylistic/comma-spacing': ['error', {
				before: false,
				after: true,
			}],
			'stylistic/comma-style': ['error', 'last'],
			'stylistic/computed-property-spacing': [
				'error',
				'never',
				{
					enforceForClassMembers: true,
				}
			],
			'stylistic/dot-location': ['error', 'object'],
			'stylistic/eol-last': ['error', 'always'],
			'stylistic/function-call-spacing': ['error', 'never'],
			'stylistic/function-call-argument-newline': ['error', 'consistent'],
			'stylistic/generator-star-spacing': ['error', {
				before: true,
				after: false,
			}],
			'stylistic/implicit-arrow-linebreak': ['error', 'beside'],
			'stylistic/indent': [
				'error',
				'tab',
				{
					ignoredNodes: ['ConditionalExpression'],
				}
			],
			'stylistic/jsx-quotes': ['error', 'prefer-single'],
			'stylistic/key-spacing': ['error', {
				beforeColon: false,
				afterColon: true,
				mode: 'strict',
			}],
			'stylistic/keyword-spacing': ['error', {
				before: true,
				after: true,
			}],
			'stylistic/lines-around-comment': ['error', {
				beforeBlockComment: true,
				beforeLineComment: true,
				allowBlockStart: true,
				allowBlockEnd: true,
				allowClassStart: true,
				allowClassEnd: true,
			}],
			'stylistic/padding-line-between-statements': [
				'error',
				{
					blankLine: 'always',
					prev: '*',
					next: ['return', 'if', 'switch', 'for', 'while', 'try', 'throw', 'function'],
				},
				{
					blankLine: 'any',
					prev: ['const', 'let', 'var', 'import'],
					next: ['const', 'let', 'var', 'import'],
				},
			],
			'stylistic/lines-between-class-members': ['error', 'never'],
			'stylistic/max-len': [
				'error',
				{
					code: 150,
					comments: 400,
					ignorePattern: '^import .*',
					ignoreStrings: true,
					ignoreTemplateLiterals: true,
					ignoreTrailingComments: true,
				},
			],
			'stylistic/max-statements-per-line': ['error', {
				max: 1,
			}],
			'stylistic/new-parens': 'error',
			'stylistic/newline-per-chained-call': ['error', {
				ignoreChainWithDepth: 2,
			}],
			'stylistic/no-extra-semi': 'error',
			'stylistic/no-floating-decimal': 'error',
			'stylistic/no-mixed-spaces-and-tabs': ['error', 'smart-tabs'],
			'stylistic/no-multi-spaces': 'error',
			'stylistic/no-multiple-empty-lines': 'error',
			'stylistic/function-call-spacing': ['error', 'never'],
			'stylistic/no-trailing-spaces': 'error',
			'stylistic/no-whitespace-before-property': 'error',
			'stylistic/object-curly-newline': ['error', {
				consistent: true,
			}],
			'stylistic/object-curly-spacing': ['error', 'never'],
			'stylistic/quote-props': ['error', 'consistent'],
			'stylistic/quotes': ['error', 'single'],
			'stylistic/rest-spread-spacing': ['error', 'never'],
			'stylistic/semi': ['error', 'always'],
			'stylistic/space-before-blocks': 'error',
			'stylistic/space-in-parens': ['error', 'never'],
			'stylistic/space-infix-ops': 'error',
			'stylistic/space-unary-ops': 'error',
			'stylistic/spaced-comment': ['error', 'always'],
			'stylistic/template-curly-spacing': 'error',
		},
	},
]);
