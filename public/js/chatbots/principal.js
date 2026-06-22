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

eval("\n\n$(function () {\n  // Feedback simple con jQuery al pulsar botones de acción\n  $(\".bot-card .btn\").on(\"click\", function () {\n    var accion = $(this).text().trim();\n    var bot = $(this).closest(\".bot-card\").find(\"h3\").text().trim();\n    console.log(\"[GIJAC] Acción: \" + accion + \" | Módulo: \" + bot);\n    var $btn = $(this);\n    var original = $btn.html();\n    $btn.prop(\"disabled\", true).html('<span class=\"spinner-border spinner-border-sm me-1\"></span>Procesando...');\n    setTimeout(function () {\n      $btn.prop(\"disabled\", false).html(original);\n    }, 900);\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY2hhdGJvdHMvcHJpbmNpcGFsLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFhOztBQUViQSxDQUFDLENBQUMsWUFBWTtFQUNWO0VBQ0FBLENBQUMsQ0FBQyxnQkFBZ0IsQ0FBQyxDQUFDQyxFQUFFLENBQUMsT0FBTyxFQUFFLFlBQVc7SUFDdkMsSUFBSUMsTUFBTSxHQUFHRixDQUFDLENBQUMsSUFBSSxDQUFDLENBQUNHLElBQUksQ0FBQyxDQUFDLENBQUNDLElBQUksQ0FBQyxDQUFDO0lBQ2xDLElBQUlDLEdBQUcsR0FBR0wsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDTSxPQUFPLENBQUMsV0FBVyxDQUFDLENBQUNDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQ0osSUFBSSxDQUFDLENBQUMsQ0FBQ0MsSUFBSSxDQUFDLENBQUM7SUFDL0RJLE9BQU8sQ0FBQ0MsR0FBRyxDQUFDLGtCQUFrQixHQUFHUCxNQUFNLEdBQUcsYUFBYSxHQUFHRyxHQUFHLENBQUM7SUFFOUQsSUFBSUssSUFBSSxHQUFHVixDQUFDLENBQUMsSUFBSSxDQUFDO0lBQ2xCLElBQUlXLFFBQVEsR0FBR0QsSUFBSSxDQUFDRSxJQUFJLENBQUMsQ0FBQztJQUMxQkYsSUFBSSxDQUFDRyxJQUFJLENBQUMsVUFBVSxFQUFFLElBQUksQ0FBQyxDQUFDRCxJQUFJLENBQzVCLDBFQUEwRSxDQUFDO0lBQy9FRSxVQUFVLENBQUMsWUFBVztNQUNsQkosSUFBSSxDQUFDRyxJQUFJLENBQUMsVUFBVSxFQUFFLEtBQUssQ0FBQyxDQUFDRCxJQUFJLENBQUNELFFBQVEsQ0FBQztJQUMvQyxDQUFDLEVBQUUsR0FBRyxDQUFDO0VBQ1gsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL2NoYXRib3RzL3ByaW5jaXBhbC5qcz8wZjI5Il0sInNvdXJjZXNDb250ZW50IjpbIlwidXNlIHN0cmljdFwiO1xuXG4kKGZ1bmN0aW9uICgpIHtcbiAgICAvLyBGZWVkYmFjayBzaW1wbGUgY29uIGpRdWVyeSBhbCBwdWxzYXIgYm90b25lcyBkZSBhY2Npw7NuXG4gICAgJChcIi5ib3QtY2FyZCAuYnRuXCIpLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBhY2Npb24gPSAkKHRoaXMpLnRleHQoKS50cmltKCk7XG4gICAgICAgIHZhciBib3QgPSAkKHRoaXMpLmNsb3Nlc3QoXCIuYm90LWNhcmRcIikuZmluZChcImgzXCIpLnRleHQoKS50cmltKCk7XG4gICAgICAgIGNvbnNvbGUubG9nKFwiW0dJSkFDXSBBY2Npw7NuOiBcIiArIGFjY2lvbiArIFwiIHwgTcOzZHVsbzogXCIgKyBib3QpO1xuXG4gICAgICAgIHZhciAkYnRuID0gJCh0aGlzKTtcbiAgICAgICAgdmFyIG9yaWdpbmFsID0gJGJ0bi5odG1sKCk7XG4gICAgICAgICRidG4ucHJvcChcImRpc2FibGVkXCIsIHRydWUpLmh0bWwoXG4gICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJzcGlubmVyLWJvcmRlciBzcGlubmVyLWJvcmRlci1zbSBtZS0xXCI+PC9zcGFuPlByb2Nlc2FuZG8uLi4nKTtcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICRidG4ucHJvcChcImRpc2FibGVkXCIsIGZhbHNlKS5odG1sKG9yaWdpbmFsKTtcbiAgICAgICAgfSwgOTAwKTtcbiAgICB9KTtcbn0pO1xuIl0sIm5hbWVzIjpbIiQiLCJvbiIsImFjY2lvbiIsInRleHQiLCJ0cmltIiwiYm90IiwiY2xvc2VzdCIsImZpbmQiLCJjb25zb2xlIiwibG9nIiwiJGJ0biIsIm9yaWdpbmFsIiwiaHRtbCIsInByb3AiLCJzZXRUaW1lb3V0Il0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/chatbots/principal.js\n");

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