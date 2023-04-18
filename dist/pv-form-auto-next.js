/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./assets/js/pv-form-auto-next.js ***!
  \****************************************/
document.addEventListener('DOMContentLoaded', () => {
  const parent = document.querySelector('.pv-calculator');
  parent.addEventListener('click', e => {
    if (e.target.type === 'radio') {
      setTimeout(function () {
        document.querySelector('.frm_button_submit').click();
      }, 50);
    }
  });
});
/******/ })()
;
//# sourceMappingURL=pv-form-auto-next.js.map