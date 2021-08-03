const admin = {
    init: function (params) {
        this.params = params;

    },
    addEventListeners: function () {
        let _this = this;

    },
};

jQuery(document).ready(function () {
    if (window.starterNPMParams) {
        admin.init(window.starterNPMParams);
    }
});
