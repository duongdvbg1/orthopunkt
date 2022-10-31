var config = {
    map: {
        '*': {
            'lazysizes': 'Rokanthemes_OptimizeSpeed/js/resource/lazysizes/lazysizes.min',
        }
    },
    deps: [
        'lazysizes',
        'Rokanthemes_OptimizeSpeed/js/resource/lazysizes/ls.native-loading.min',
        'Rokanthemes_OptimizeSpeed/js/resource/lazysizes/ls.bgset.min',
    ],
    shim: {
        'Rokanthemes_OptimizeSpeed/js/resource/lazysizes/lazysizes.min': {
            exports: 'lazySizes',
        },
    },
};