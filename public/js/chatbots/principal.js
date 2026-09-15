/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/chatbots/principal.js":
/*!********************************************!*\
  !*** ./resources/js/chatbots/principal.js ***!
  \********************************************/
/***/ (() => {

eval("\n\n$(function () {\n  // Feedback simple con jQuery al pulsar botones de acción\n  $(\".bot-card .btn\").on(\"click\", function () {\n    var accion = $(this).text().trim();\n    var bot = $(this).closest(\".bot-card\").find(\"h3\").text().trim();\n    console.log(\"[GIJAC] Acción: \" + accion + \" | Módulo: \" + bot);\n    var $btn = $(this);\n    var original = $btn.html();\n    $btn.prop(\"disabled\", true).html(\"<span class=\\\"spinner-border spinner-border-sm me-1\\\"></span>\".concat(__('Procesando...')));\n    setTimeout(function () {\n      $btn.prop(\"disabled\", false).html(original);\n    }, 900);\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY2hhdGJvdHMvcHJpbmNpcGFsLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFhOztBQUViQSxDQUFDLENBQUMsWUFBWTtFQUNWO0VBQ0FBLENBQUMsQ0FBQyxnQkFBZ0IsQ0FBQyxDQUFDQyxFQUFFLENBQUMsT0FBTyxFQUFFLFlBQVc7SUFDdkMsSUFBSUMsTUFBTSxHQUFHRixDQUFDLENBQUMsSUFBSSxDQUFDLENBQUNHLElBQUksQ0FBQyxDQUFDLENBQUNDLElBQUksQ0FBQyxDQUFDO0lBQ2xDLElBQUlDLEdBQUcsR0FBR0wsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDTSxPQUFPLENBQUMsV0FBVyxDQUFDLENBQUNDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQ0osSUFBSSxDQUFDLENBQUMsQ0FBQ0MsSUFBSSxDQUFDLENBQUM7SUFDL0RJLE9BQU8sQ0FBQ0MsR0FBRyxDQUFDLGtCQUFrQixHQUFHUCxNQUFNLEdBQUcsYUFBYSxHQUFHRyxHQUFHLENBQUM7SUFFOUQsSUFBSUssSUFBSSxHQUFHVixDQUFDLENBQUMsSUFBSSxDQUFDO0lBQ2xCLElBQUlXLFFBQVEsR0FBR0QsSUFBSSxDQUFDRSxJQUFJLENBQUMsQ0FBQztJQUMxQkYsSUFBSSxDQUFDRyxJQUFJLENBQUMsVUFBVSxFQUFFLElBQUksQ0FBQyxDQUFDRCxJQUFJLGlFQUFBRSxNQUFBLENBQ2tDQyxFQUFFLENBQUMsZUFBZSxDQUFDLENBQUUsQ0FBQztJQUN4RkMsVUFBVSxDQUFDLFlBQVc7TUFDbEJOLElBQUksQ0FBQ0csSUFBSSxDQUFDLFVBQVUsRUFBRSxLQUFLLENBQUMsQ0FBQ0QsSUFBSSxDQUFDRCxRQUFRLENBQUM7SUFDL0MsQ0FBQyxFQUFFLEdBQUcsQ0FBQztFQUNYLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9jaGF0Ym90cy9wcmluY2lwYWwuanM/MGYyOSJdLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcblxuJChmdW5jdGlvbiAoKSB7XG4gICAgLy8gRmVlZGJhY2sgc2ltcGxlIGNvbiBqUXVlcnkgYWwgcHVsc2FyIGJvdG9uZXMgZGUgYWNjacOzblxuICAgICQoXCIuYm90LWNhcmQgLmJ0blwiKS5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgYWNjaW9uID0gJCh0aGlzKS50ZXh0KCkudHJpbSgpO1xuICAgICAgICB2YXIgYm90ID0gJCh0aGlzKS5jbG9zZXN0KFwiLmJvdC1jYXJkXCIpLmZpbmQoXCJoM1wiKS50ZXh0KCkudHJpbSgpO1xuICAgICAgICBjb25zb2xlLmxvZyhcIltHSUpBQ10gQWNjacOzbjogXCIgKyBhY2Npb24gKyBcIiB8IE3Ds2R1bG86IFwiICsgYm90KTtcblxuICAgICAgICB2YXIgJGJ0biA9ICQodGhpcyk7XG4gICAgICAgIHZhciBvcmlnaW5hbCA9ICRidG4uaHRtbCgpO1xuICAgICAgICAkYnRuLnByb3AoXCJkaXNhYmxlZFwiLCB0cnVlKS5odG1sKFxuICAgICAgICAgICAgYDxzcGFuIGNsYXNzPVwic3Bpbm5lci1ib3JkZXIgc3Bpbm5lci1ib3JkZXItc20gbWUtMVwiPjwvc3Bhbj4ke19fKCdQcm9jZXNhbmRvLi4uJyl9YCk7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAkYnRuLnByb3AoXCJkaXNhYmxlZFwiLCBmYWxzZSkuaHRtbChvcmlnaW5hbCk7XG4gICAgICAgIH0sIDkwMCk7XG4gICAgfSk7XG59KTtcbiJdLCJuYW1lcyI6WyIkIiwib24iLCJhY2Npb24iLCJ0ZXh0IiwidHJpbSIsImJvdCIsImNsb3Nlc3QiLCJmaW5kIiwiY29uc29sZSIsImxvZyIsIiRidG4iLCJvcmlnaW5hbCIsImh0bWwiLCJwcm9wIiwiY29uY2F0IiwiX18iLCJzZXRUaW1lb3V0Il0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/chatbots/principal.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/chatbots/principal.js"]();
/******/ 	
/******/ })()
;