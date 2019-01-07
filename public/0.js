(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  mounted: function mounted() {
    console.log('Component mounted.');
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      adminMode: false,
      sliders: null,
      form: new Form({
        title: '',
        photos: '',
        description: ''
      })
    };
  },
  methods: {
    getSliders: function getSliders() {
      var _this = this;

      axios.get("/api/Sliders").then(function (_ref) {
        var data = _ref.data;
        _this.sliders = data.data;
      });
    },
    showModal: function showModal() {
      this.form.reset();
      $("#sliderModal").modal("show");
    },
    onFileSelect: function onFileSelect(event) {
      var _this2 = this;

      var file = event.target.files[0];
      this.form.photos = event.target.files[0];
      var reader = new FileReader();

      reader.onloadend = function () {
        _this2.form.photos = reader.result;
      };

      reader.readAsDataURL(file);
    },
    createSlider: function createSlider() {
      var _this3 = this;

      if (this.$gate.isAdmin()) {
        this.form.post('/api/Sliders').then(function () {
          toast({
            type: 'success',
            title: 'Успешно добавихте нов слайд!'
          });
          $('#sliderModal').modal('hide');

          _this3.getSliders();
        }).catch(function () {});
      }
    },
    changeMode: function changeMode() {
      if (this.$gate.isAdmin()) {
        this.adminMode = true;
      } else {
        this.adminMode = false;
      }
    }
  },
  mounted: function mounted() {
    this.getSliders();
    this.changeMode();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-12 col-md-4" }, [
      _c("div", { staticClass: "card-header" }, [_vm._v("Example Component")])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "col-12 col-md-8" },
    [
      _c(
        "a",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.adminMode,
              expression: "adminMode"
            }
          ],
          attrs: { href: "#" },
          on: {
            click: function($event) {
              _vm.showModal()
            }
          }
        },
        [_c("i", { staticClass: "fas fa-file-import fa-3x green" })]
      ),
      _vm._v(" "),
      _vm._l(_vm.sliders, function(slider) {
        return _c(
          "div",
          { key: slider.id },
          [
            _c(
              "router-link",
              {
                staticClass: "nav-link",
                attrs: { to: { name: "За-спорта", params: { id: slider.id } } }
              },
              [
                _c("div", [
                  _c("p", [_vm._v(_vm._s(slider.title))]),
                  _vm._v(" "),
                  _c("img", {
                    attrs: {
                      src: "/storage/sliders/" + slider.photos,
                      alt: slider.title
                    }
                  })
                ])
              ]
            )
          ],
          1
        )
      }),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "modal fade",
          attrs: {
            id: "sliderModal",
            tabindex: "-1",
            role: "dialog",
            "aria-labelledby": "sliderModalLabel",
            "aria-hidden": "true"
          }
        },
        [
          _c(
            "div",
            { staticClass: "modal-dialog", attrs: { role: "document" } },
            [
              _c("div", { staticClass: "modal-content" }, [
                _vm._m(0),
                _vm._v(" "),
                _c(
                  "form",
                  {
                    attrs: {
                      id: "slidersForm",
                      enctype: "multipart/form-data"
                    },
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        _vm.createSlider()
                      }
                    }
                  },
                  [
                    _c("div", { staticClass: "modal-body" }, [
                      _c(
                        "div",
                        { staticClass: "form-group" },
                        [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.title,
                                expression: "form.title"
                              }
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid": _vm.form.errors.has("title")
                            },
                            attrs: {
                              type: "text",
                              name: "title",
                              placeholder: "Заглавие",
                              required: ""
                            },
                            domProps: { value: _vm.form.title },
                            on: {
                              input: function($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(_vm.form, "title", $event.target.value)
                              }
                            }
                          }),
                          _vm._v(" "),
                          _c("has-error", {
                            attrs: { form: _vm.form, field: "title" }
                          })
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("div", { staticClass: "form-group" }, [
                        _c("input", {
                          staticClass: "form-control",
                          attrs: { type: "file", name: "photos" },
                          on: { change: _vm.onFileSelect }
                        })
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "form-group" },
                        [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.description,
                                expression: "form.description"
                              }
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid": _vm.form.errors.has("description")
                            },
                            attrs: {
                              type: "text",
                              name: "description",
                              placeholder: "Описание",
                              required: ""
                            },
                            domProps: { value: _vm.form.description },
                            on: {
                              input: function($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "description",
                                  $event.target.value
                                )
                              }
                            }
                          }),
                          _vm._v(" "),
                          _c("has-error", {
                            attrs: { form: _vm.form, field: "description" }
                          })
                        ],
                        1
                      )
                    ]),
                    _vm._v(" "),
                    _vm._m(1)
                  ]
                )
              ])
            ]
          )
        ]
      )
    ],
    2
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "modal-header" }, [
      _c(
        "h5",
        { staticClass: "modal-title", attrs: { id: "sliderModalLabel" } },
        [_vm._v("Добавяне на слайд")]
      ),
      _vm._v(" "),
      _c(
        "button",
        {
          staticClass: "close",
          attrs: {
            type: "button",
            "data-dismiss": "modal",
            "aria-label": "Close"
          }
        },
        [_c("span", { attrs: { "aria-hidden": "true" } }, [_vm._v("×")])]
      )
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "modal-footer" }, [
      _c(
        "button",
        { staticClass: "btn btn-success", attrs: { type: "submit" } },
        [_vm._v("Добави")]
      ),
      _vm._v(" "),
      _c(
        "button",
        {
          staticClass: "btn btn-secondary",
          attrs: { type: "button", "data-dismiss": "modal" }
        },
        [_vm._v("Затвори")]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/content/news/AnnouncementComponent.vue":
/*!************************************************************************!*\
  !*** ./resources/js/components/content/news/AnnouncementComponent.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AnnouncementComponent.vue?vue&type=template&id=ca10100a& */ "./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a&");
/* harmony import */ var _AnnouncementComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AnnouncementComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AnnouncementComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/content/news/AnnouncementComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnnouncementComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AnnouncementComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AnnouncementComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AnnouncementComponent.vue?vue&type=template&id=ca10100a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/news/AnnouncementComponent.vue?vue&type=template&id=ca10100a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AnnouncementComponent_vue_vue_type_template_id_ca10100a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/content/slider/SlidersComponent.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/components/content/slider/SlidersComponent.vue ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SlidersComponent.vue?vue&type=template&id=6dfdbb30& */ "./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30&");
/* harmony import */ var _SlidersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SlidersComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SlidersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/content/slider/SlidersComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SlidersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./SlidersComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SlidersComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30& ***!
  \****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./SlidersComponent.vue?vue&type=template&id=6dfdbb30& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/content/slider/SlidersComponent.vue?vue&type=template&id=6dfdbb30&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SlidersComponent_vue_vue_type_template_id_6dfdbb30___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);