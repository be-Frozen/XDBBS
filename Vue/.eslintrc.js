/* eslint-disable */
module.exports = {
    globals: {
        defineProps: "readonly",
        defineEmits: "readonly"
    },
    "env": {
        "browser": true,
        "es2021": true,
        "node":true,

    },
    "extends": [
        "plugin:vue/essential",
        "google"
    ],
    "parserOptions": {
        "ecmaVersion": 12,
        "sourceType": "module"
    },
    "plugins": [
        "vue"
    ],
    "rules": {

    }
};
