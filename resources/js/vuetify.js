import {createVuetify} from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import '@mdi/font/css/materialdesignicons.css';
//import {VuetifyDateAdapter} from 'vuetify/lib/labs/date/adapters/vuetify'

const customTheme = {
    dark: false,
    colors: {
        primary: '#1DD185',
        secondary: '#FFC107',
        accent: '#FF5722',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107',
        lightblue: "#14c6FF",
        yellow: "#FFCF00",
        pink: "#FF1976",
        orange: "#FF8657",
        magenta: "#C33AFC",
        darkblue: "#1E2D56",
        gray: "#909090",
        neutralgray: "#9BA6C1",
        green: "#2ED47A",
        red: "#FF5c4E",
        darkblueshade: "#308DC2",
        lightgray: "#BDBDBD",
        lightpink: "#FFCFE3",
        white: "#FFFFFF",
        muted: "#6c757d",
    },
};

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "customTheme",
        themes: {
            customTheme: customTheme,
        },
    },
    icons: {
        iconfont: 'mdi',
    },
    date: {
    //    adapter: VuetifyDateAdapter,
    },
    buttons: {
        capitalize: false,
    },
});

export default vuetify;