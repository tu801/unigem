/**
 * @author brianha289
 */

const vLog = Vue.createApp({
    data() {
        return {
            lstSysLogin: [],
            logTab: "sysact",
            curPageOfSysAct: 0,
            pagesOfSysAct: 0,
            lstSysAct: [],
            action: "",
            dataTable: null,
            loading: false,
            selSysActModule: '',
            changeSysActKeyword: undefined,
            // isOneTime: false,
        };
    },
    created: function () {
        this.nxtSysActBy(1);
        this.fetchModuleSysAct();
    },
    methods: {
        isActive(menuItem) {
            return this.logTab === menuItem
        },
        setActive(menuItem) {
            this.logTab = menuItem;
            //this.fetchModuleSysAct();
            this.nxtSysActBy(1);
        },

        nxtSysActBy(page) {
            this.loading = true;
            var sysActUrl =
                bkUrl +
                (this.logTab == "sysact" ? "/log/lst-sys-act" : "/log/lst-sys-login") +
                "?page=" +
                (page != undefined ? page : 1);
            if (this.selSysActModule && this.logTab == "sysact") {
                sysActUrl = sysActUrl + "&mod=" + this.selSysActModule;
            }
            if (this.changeSysActKeyword) {
                sysActUrl = sysActUrl + "&keyword=" + this.changeSysActKeyword;
            }
            console.log("\nSend url", sysActUrl);
            $.ajax({
                url: sysActUrl,
                method: "GET",
                dataType: "json",
                success: (response) => {
                    if (response.error == 0) {
                        //console.log("All data:", response);
                        this.lstSysAct = response.data;
                        this.curPageOfSysAct = response.curPage;
                        this.pagesOfSysAct = response.pages;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi...",
                            text: response.message,
                        });
                    }
                    this.loading = false;
                },
                error: (_xhr, _error) => {
                    this.loading = false;
                },
            });
        },
        targetNextPage(page) {
            this.nxtSysActBy(page);
        },
        fetchModuleSysAct() {
            var moduleSysAct = bkUrl + "/log/mod-sys-act";
            $.ajax({
                url: moduleSysAct,
                method: "GET",
                dataType: "json",
                success: (response) => {
                    if (response.error == 0) {
                        if (
                            typeof response.sysModule === "object" &&
                            response.sysModule.length > 0
                        ) {
                            console.log("All data:", response);
                            let sltItem = $(".select-module").attr("selected-item");
                            for (let i = 0; i < response.sysModule.length; i++) {
                                let selectedItem = false;
                                if (
                                    typeof sltItem !== "undefined" &&
                                    sltItem == response.sysModule[i].val
                                )
                                    selectedItem = true;

                                var newOption = new Option(
                                    response.sysModule[i].name,
                                    response.sysModule[i].val,
                                    selectedItem,
                                    selectedItem
                                );
                                // Append it to the select
                                if (i == response.sysModule.length - 1)
                                    $(".select-module").append(newOption).trigger("change");
                                else $(".select-module").append(newOption);
                            }
                        }
                    }
                },
            });
        },
        selectSysModule(key) {
            this.selSysActModule = key;
            this.nxtSysActBy(1);
        },
        changeSysKeyword(keyword) {
            this.changeSysActKeyword = keyword;
            this.nxtSysActBy(1);
        },
    },
});

vLog.config.globalProperties.$filters = {
    decodeJSON(value) {
        if (!value) return "";
        return JSON.parse(value);
    },
};
// add: sys_act_tab
vLog.component("vsys-act-tab", {
    template: "#vsys-act-tab-tmpl",
    props: ["sys_act_data", "cur_page", "pages", 'sltModule'],
    data() {
        return {
            optSelected: this.sltModule
        }
    },
    methods: {
        targetPage(pageIdx) {
            this.$emit("em-target-page", pageIdx);
        },
        onSysModuleChanged(event) {
            console.log(event.target.value);
            this.$emit("em-sys-module", event.target.value);
        },
        onKeywordChanged(event) {
            console.log(event.target.value);
            this.$emit("em-sys-keyword", event.target.value);
        },
    },
});

// add: vsys-login-tab-tmpl
vLog.component("vsys-login-tab", {
    template: "#vsys-login-tab-tmpl",
    props: ["sys_act_data", "cur_page", "pages"],
    methods: {
        targetPage(pageIdx) {
            this.$emit("em-target-page", pageIdx);
        },
        onSysModuleChanged(event) {
            console.log(event.target.value);
            this.$emit("em-sys-module", event.target.value);
        },
        onKeywordChanged(event) {
            console.log(event.target.value);
            this.$emit("em-sys-keyword", event.target.value);
        },
    },
});

vLog.mount("#lstSysLog");
