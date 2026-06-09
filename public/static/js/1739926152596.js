async function e() {
	window.siteRoutes = [{
		name: "Inicio",
		path: "inicio",
		title: "Inicio - Tu Solución Ideal"
	}, {
		name: "Precios",
		path: "precios",
		title: "Precios - Tu Solución Ideal"
	}, {
		name: "Casos de Éxito",
		path: "casos-de-exito",
		title: "Casos de Éxito - Tu Solución Ideal"
	}], window.attachments = [], window.turi = {
		botUrl: null,
		botStatus: 0
	};
	const e = [],
		t = await siteEngine.getPackage("createRoot"),
		a = await siteEngine.getPackage("React"),
		o = await siteEngine.getPackage("ErrorBoundary"),
		n = await siteEngine.getPackage("RouterWrap"),
		s = await siteEngine.getPackage("ReactRouterDom"),
		l = s.createBrowserRouter,
		i = s.RouterProvider,
		r = (s.Navigate, await siteEngine.getPackage("@Basic/AnimateInView")),
		c = await siteEngine.getPackage("@Basic/EditableImg"),
		m = await siteEngine.getPackage("@Basic/EditableText"),
		d = await siteEngine.getPackage("@Basic/EditableButton"),
		p = await siteEngine.getPackage("@Basic/EditableIcon"),
		u = (await siteEngine.getPackage("@Basic/EditableMedia"), await siteEngine.getPackage("@Basic/EditableEmbed"), await siteEngine.getPackage("@Basic/EmbedPopover")),
		g = await siteEngine.getPackage("@Basic/EmbedSidetab"),
		x = await siteEngine.getPackage("@Basic/AIChatBox"),
		f = (await siteEngine.getPackage("@Basic/ComponentSlot"), await siteEngine.getPackage("Marquee")),
		b = (await siteEngine.getPackage("Carousel"), await siteEngine.getPackage("motion")),
		E = (await siteEngine.getPackage("framerMotion"), await siteEngine.getPackage("AnimatePresence"));
	let h = a.createElement(a.Fragment, null),
		y = a.createElement(a.Fragment, null);

	function w(e) {
		return a.createElement(n, null, h, e.children, y, a.createElement(u, null), a.createElement(g, null), a.createElement(x, null))
	}
	try {
		h = a.createElement(o, {
			key: "W8FYOXaLJ1FDHZsx9Qe8k"
		}, a.createElement(function({
			logo: e = "https://images.unsplash.com/photo-1667120564541-b6368f7b1914?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wzNjAzNTV8MHwxfHJhbmRvbXx8fHx8fHx8fDE3MjI0NzY2Mjd8&ixlib=rb-4.0.3&q=80&w=1200",
			navItems: t = ["text=Home&link=/home", "text=About&link=/about", "text=Service&link=/service", "text=Case&link=/case", "text=Blog&link=/blog", "text=Contact&link=/contact", "text=Localtion&link=/localtion", "text=Map&link=/map"],
			primaryButton: o = {
				icon: "fa-solid fa-arrow-right",
				textAttr: "Contact us",
				textColor: "#ffffff"
			},
			secondaryButton: n,
			fixedTop: s = !0,
			logoSize: l = 40,
			showButton: i = !0
		}) {
			const [r, u] = a.useState(!1), [g, x] = a.useState(!1), [f, h] = a.useState(!1), [y, w] = a.useState(!1), v = a.useRef(0), [k, N] = a.useState(!1), C = a.useRef(null), T = e => {
				e.target !== C.current && r && u(!1)
			}, [P, A] = a.useState({
				buttonH: 40,
				buttonPX: 20,
				buttonFont: 14,
				outerPadding: 12,
				dropDownPaddingl: 12,
				dropDownPaddingt: 12,
				dropDownPaddingb: 12,
				dropDownPaddingr: 48,
				dropDownItemLeading: 24,
				dropDownItemFontSize: 14,
				mobileDropDownPaddingY: 20,
				mobileDropDownGap: 8,
				mobileDropDownItemH: 72
			});
			return a.useEffect(() => {
				let e;
				l <= 48 ? e = {
					buttonH: 40,
					buttonPX: 20,
					buttonFont: 14,
					outerPadding: 12,
					dropDownPaddingl: 12,
					dropDownPaddingt: 12,
					dropDownPaddingb: 12,
					dropDownPaddingr: 48,
					dropDownItemLeading: 24,
					dropDownItemFontSize: 14,
					mobileDropDownPaddingY: 20,
					mobileDropDownGap: 8,
					mobileDropDownItemH: 72
				} : l > 48 && l <= 56 ? e = {
					buttonH: 48,
					buttonPX: 20,
					buttonFont: 14,
					outerPadding: 20,
					dropDownPaddingl: 20,
					dropDownPaddingt: 20,
					dropDownPaddingb: 20,
					dropDownPaddingr: 80,
					dropDownItemLeading: 24,
					dropDownItemFontSize: 14,
					mobileDropDownPaddingY: 24,
					mobileDropDownGap: 16,
					mobileDropDownItemH: 80
				} : l > 56 && (e = {
					buttonH: 56,
					buttonPX: 28,
					buttonFont: 16,
					outerPadding: 28,
					dropDownPaddingl: 20,
					dropDownPaddingt: 20,
					dropDownPaddingb: 20,
					dropDownPaddingr: 80,
					dropDownItemLeading: 28,
					dropDownItemFontSize: 14,
					mobileDropDownPaddingY: 28,
					mobileDropDownGap: 24,
					mobileDropDownItemH: 96
				}), A(e)
			}, [l]), a.useEffect(() => {
				var e;
				const t = window.innerHeight,
					a = ((e, a) => {
						let o = 0;
						return function(e) {
							let a = Date.now();
							a - o > 16.7 && ((e => {
								(e => {
									if (e.currentTarget) {
										let a = e.currentTarget.scrollTop;
										if (a >= t / 10 ? !y && w(!0) : y && w(!1), a < t / 2) return void(k && N(!1));
										v.current < a ? a >= t / 2 && !k && N(!0) : N(!1), v.current = a
									}
								})(e)
							})(e), o = a)
						}
					})();
				let o = (null === (e = window) || void 0 === e || null === (e = e.siteEngine) || void 0 === e ? void 0 : e.scrollContainerId) || "preview-viewport",
					n = window.document.getElementById(o);
				return n && n.addEventListener("scroll", a), () => {
					n && n.removeEventListener("scroll", a), n = null
				}
			}, [y, s, k]), a.useEffect(() => {
				let e = 0;
				i ? (o && e++, n && e++, e += t.length) : e += t.length, h(!(e <= 1)), e <= 1 && x(!1)
			}, [o, n, t, i]), a.useEffect(() => (window.addEventListener("click", T), () => {
				window.removeEventListener("click", T)
			}), [r]), a.createElement("section", {
				style: {
					boxShadow: y ? "0px 4px 40px 0px #0000000A" : ""
				},
				className: `fixed ${g ? "rounded-tl-3xl rounded-tr-3xl" : "rounded-[200px]"} outline-1 outline-black/5 left-8 right-8 top-5 z-40 md:${y ? "rounded-[200px]" : "rounded-[0px]"} md:${k && !s ? "top-[-115px] opacity-0 transition-opacity duration-100" : y ? "top-6" : "top-0"} md:${y ? "left-8" : "left-0 "} md:border md:border-black/5 md:${y ? "right-8" : "right-0"} bg-white backdrop-blur-xl dark:bg-black/80 md:px-8 md:pr-5 md:transition-all md:duration-500 ${y ? "max-w-[1376px] m-auto" : "m-auto"}`
			}, a.createElement("div", {
				style: {
					paddingTop: `${P.outerPadding}px`,
					paddingBottom: `${P.outerPadding}px`
				},
				className: "w-full mx-auto max-w-7xl h-full hidden gap-5 md:flex md:items-center"
			}, a.createElement("div", {
				className: "w-full h-full flex items-center justify-start"
			}, a.createElement(c, {
				propKey: "logo",
				style: {
					height: `${l}px`
				},
				className: "!w-auto object-cover",
				src: e,
				alt: "logo"
			})), a.createElement("div", {
				className: "w-full h-full flex items-center justify-center text-sm leading-[17px]"
			}, t.length > 3 ? a.createElement("nav", {
				className: `w-full h-full flex items-center justify-${i ? "center" : "end"} gap-5 text-[${P.buttonFont}px]`
			}, t.slice(0, 3).map((e, t) => a.createElement(d, {
				key: e,
				className: "py-3 text-black whitespace-nowrap cursor-pointer rounded-[32px] px-4 hover:bg-black/5 hover:transition-all duration-100 dark:text-white dark:hover:text-white/60",
				onClick: () => u(!1)
			}, a.createElement(m, {
				propKey: `navItems_${t}`
			}, e))), a.createElement("div", {
				className: "relative"
			}, a.createElement(b.button, {
				className: "py-1 px-1 flex items-center rounded-full hover:bg-black/5 hover:transition-all duration-100",
				onClick: () => u(!r)
			}, a.createElement("svg", {
				ref: C,
				xmlns: "http://www.w3.org/2000/svg",
				viewBox: "0 0 24 24",
				fill: "currentColor",
				className: "w-8 h-8 fill-black dark:fill-white dark:hover:fill-white/60"
			}, a.createElement("path", {
				"fill-rule": "evenodd",
				d: "M4.5 12a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm6 0a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm6 0a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z",
				"clip-rule": "evenodd"
			}))), r && a.createElement(E, null, a.createElement(b.div, {
				className: `absolute z-40 right-0 flex flex-col p-[10px] gap-1 origin-top rounded-lg bg-white border border-black/10 dark:border-white/10 shadow-lg dark:bg-black text-[${P.buttonFont}px]`,
				animate: {
					scale: [0, 1],
					opacity: [0, 1]
				}
			}, t.slice(3, t.length).map((e, t) => a.createElement(d, {
				key: e,
				style: {
					paddingTop: `${P.dropDownPaddingt}px`,
					paddingBottom: `${P.dropDownPaddingb}px`,
					paddingLeft: `${P.dropDownPaddingl}px`,
					paddingRight: `${P.dropDownPaddingr}px`,
					lineHeight: `${P.dropDownItemLeading}px`,
					fontSize: `${P.dropDownItemFontSize}px`
				},
				className: "w-full h-full text-black whitespace-nowrap cursor-pointer text-left hover:bg-black/5 rounded-full hover:transition-all duration-100 dark:text-white dark:hover:text-white/60",
				onClick: () => u(!r)
			}, a.createElement(m, {
				style: {},
				link: e.pagePath,
				propKey: `navItems_${t + 3}`
			}, e))))))) : a.createElement("nav", {
				className: `${t.length <= 1 ? "hidden" : ""} w-full h-full flex items-center justify-${i ? "center" : "end"} text-[${P.buttonFont}px]`
			}, t.map((e, t) => a.createElement(d, {
				key: e,
				className: "pr-12 py-3 text-black whitespace-nowrap cursor-pointer hover:text-black/60 hover:transition-all duration-100 dark:text-white dark:hover:text-white/60"
			}, a.createElement(m, {
				propKey: `navItems_${t}`
			}, e))))), a.createElement("div", {
				className: `${i ? "" : "hidden"} w-full h-full flex items-center justify-end overflow-hidden gap-3`
			}, n && a.createElement(d, {
				style: {
					height: `${P.buttonH}px`
				},
				className: `BTN-SECONDARY max-w-[180px] w-fit px-5 group text-[${P.buttonFont}px] whitespace-nowrap font-normal text-black flex gap-2 items-center rounded-full hover:bg-black/5 hover:transition-all hover:duration-300`
			}, a.createElement(m, {
				className: "overflow-hidden text-ellipsis",
				propKey: "secondaryButton_textAttr"
			}, n.textAttr), a.createElement(p, {
				propKey: "secondaryButton_icon",
				icon: n.icon,
				iconLibrary: "FontAwesome",
				className: "text-sm text-black group-hover:translate-x-1 transition-all"
			})), o && a.createElement(d, {
				style: {
					height: `${P.buttonH}px`
				},
				className: `BTN-PRIMARY max-w-[180px] w-fit px-[${P.buttonPX}px] group text-[${P.buttonFont}px] whitespace-nowrap font-normal text-black flex gap-2 items-center rounded-full bg-white/0 border border-black hover:text-[${o.textColor}] hover:bg-blue-600 hover:transition-all hover:duration-300`
			}, a.createElement(m, {
				className: "overflow-hidden text-ellipsis",
				propKey: "primaryButton_textAttr"
			}, o.textAttr), a.createElement(p, {
				propKey: "primaryButton_icon",
				icon: o.icon,
				iconLibrary: "FontAwesome",
				className: "text-s group-hover:translate-x-1 transition-transform"
			})))), a.createElement("div", {
				style: {
					paddingTop: `${P.outerPadding}px`,
					paddingBottom: `${P.outerPadding}px`
				},
				className: "relative z-40 w-full h-full flex items-center md:hidden"
			}, a.createElement(c, {
				propKey: "logo",
				style: {
					height: `${l}px`
				},
				className: "!w-auto px-6 object-cover",
				src: e,
				alt: "logo"
			}), a.createElement(b.button, {
				className: `${f ? "" : "hidden"} absolute right-6`,
				onClick: () => x(!g),
				whileTap: {
					scale: .9
				}
			}, g ? a.createElement("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				viewBox: "0 0 24 24",
				fill: "currentColor",
				className: "w-8 h-8 fill-black dark:fill-white"
			}, a.createElement("path", {
				"fill-rule": "evenodd",
				d: "M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z",
				"clip-rule": "evenodd"
			})) : a.createElement("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				viewBox: "0 0 24 24",
				fill: "currentColor",
				className: "w-8 h-8 fill-black dark:fill-white"
			}, a.createElement("path", {
				"fill-rule": "evenodd",
				d: "M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z",
				"clip-rule": "evenodd"
			}))), g ? a.createElement("div", {
				className: "absolute z-50 top-full w-full flex flex-col gap-3 bg-white rounded-b-3xl shadow-xl dark:bg-black md:hidden"
			}, a.createElement(b.nav, {
				style: {
					paddingTop: `${P.mobileDropDownPaddingY}px`,
					paddingBottom: `${P.mobileDropDownPaddingY}px`
				},
				className: `w-full gap-[${P.mobileDropDownGap}px] px-7 flex flex-col text-[${P.buttonFont}px]`,
				animate: {
					y: [20, 0],
					opacity: [0, 1]
				},
				transition: {
					duration: .4
				}
			}, t.map((e, t) => a.createElement(d, {
				key: e,
				style: {
					height: `${P.mobileDropDownItemH}px`
				},
				className: "flex justify-center items-center w-full px-6 py-3 text-black text-left whitespace-nowrap cursor-pointer rounded-full hover:bg-black/5 hover:transition-all duration-100 dark:text-white dark:hover:text-white/60",
				onClick: () => x(!g)
			}, a.createElement(m, {
				propKey: `navItems_${t}`
			}, e))), a.createElement("div", {
				className: `${i ? "" : "hidden"} w-full h-full flex items-center justify-between overflow-hidden gap-3 px-5 my-4`
			}, n && a.createElement(d, {
				style: {
					height: `${P.buttonH}px`
				},
				className: `BTN-SECONDARY px-5 flex-1 group text-[${P.buttonFont}px] justify-center overflow-hidden whitespace-nowrap font-normal text-black flex gap-2 items-center rounded-full hover:bg-black/5 hover:transition-all hover:duration-300`
			}, a.createElement(m, {
				className: "overflow-hidden text-ellipsis",
				propKey: "secondaryButton_textAttr"
			}, n.textAttr), a.createElement(p, {
				propKey: "secondaryButton_icon",
				icon: n.icon,
				iconLibrary: "FontAwesome",
				className: "text-sm text-black group-hover:translate-x-1 transition-all"
			})), o && a.createElement(d, {
				style: {
					height: `${P.buttonH}px`
				},
				className: `BTN-PRIMARY px-5 flex-1 overflow-hidden px-[${P.buttonPX}px] group text-[${P.buttonFont}px] justify-center whitespace-nowrap font-normal text-black hover:text-[${o.textColor}] flex gap-2 items-center rounded-full bg-white/0 border border-black hover:bg-blue-600 hover:transition-all hover:duration-300`
			}, a.createElement(m, {
				className: "overflow-hidden text-ellipsis",
				propKey: "primaryButton_textAttr"
			}, o.textAttr), a.createElement(p, {
				propKey: "primaryButton_icon",
				icon: o.icon,
				iconLibrary: "FontAwesome",
				className: `text-sm text-black hover:text-[${o.textColor}] group-hover:translate-x-1 transition-all`
			}))))) : null))
		}, {
			fixedTop: !1,
			logo: "https://message-business.gijac.com/img/GMB.png",
			logoSize: 40,
			siteTitle: "Tu Solución Ideal",
			navItems: ["text=Inicio&link=inicio", "text=Precios&link=precios", "text=Casos de Éxito&link=casos-de-exito"],
			showButton: !0,
			primaryButton: {
				icon: "fa-solid fa-arrow-right",
				textAttr: "text=Contáctenos",
				textColor: "#000000"
			},
			secondaryButton: {
				icon: "fa-solid fa-arrow-right",
				textAttr: "text=Únase a nosotros"
			},
			version: 2,
			key: "W8FYOXaLJ1FDHZsx9Qe8k"
		}))
	} catch (e) {} {
		const t = [];
		try {
			const e = a.createElement(o, {
				key: "3Ypgnq70gMCRidxX9-fXV"
			}, a.createElement(function({
				title: e = "Mejora tu Comunicación Empresarial",
				description: t = "Descubre cómo nuestra aplicación transforma la manera en que tu empresa se comunica a través de WhatsApp, asegurando eficiencia y resultados.",
				images: o = ["https://message-business.gijac.com/img/deepseek.jpeg", "https://message-business.gijac.com/img/Meta-1.jpg", "https://message-business.gijac.com/img/WHATSAPP.png"],
				primaryButton: n = {
					textAttr: "text=Contáctanos&link=/contact"
				},
				secondaryButton: s = {
					textAttr: "text=Más Información&link=/info"
				},
				features: l = [{
					icon: "fa-solid fa-comments",
					text: "Comunicación Instantánea"
				}, {
					icon: "fa-solid fa-shield-alt",
					text: "Seguridad Garantizada"
				}, {
					icon: "fa-solid fa-chart-line",
					text: "Resultados Medibles"
				}],
				contactInfo: i = {}
			}) {
				return a.createElement("section", {
					className: "w-full px-6 py-24 bg-white dark:bg-black md:px-8 md:py-32"
				}, a.createElement("div", {
					className: "relative w-full mx-auto max-w-7xl flex flex-col gap-24"
				}, a.createElement("div", {
					className: "w-full h-full grid grid-cols-1 items-end gap-16 md:grid-cols-2"
				}, a.createElement("div", {
					className: " w-full flex flex-col gap-16"
				}, a.createElement("h1", {
					className: "TITLE-PRIMARY text-5xl font-semibold text-slate-900 dark:text-slate-50 md:text-6xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("div", {
					className: "flex items-center gap-6"
				}, a.createElement(d, {
					className: "BTN-PRIMARY w-fit h-12 px-6 group text-sm font-semibold uppercase text-white flex gap-2 items-center rounded-full bg-[#4CAF50] hover:bg-[#45a049] hover:transition-all hover:duration-300 md:px-8 md:text-base md:h-14"
				}, a.createElement(m, {
					propKey: "primaryButton_textAttr"
				}, n.textAttr), a.createElement(p, {
					propKey: "primaryButton_icon",
					icon: n.icon,
					iconLibrary: "FontAwesome",
					className: "text-base text-white group-hover:translate-x-1 transition-all"
				})), a.createElement(d, {
					className: "BTN-SECONDARY w-fit h-12 px-6 text-sm font-semibold uppercase flex gap-2 items-center rounded-full border border-[#4CAF50] text-[#4CAF50] hover:bg-[#4CAF50] hover:text-white hover:transition-all hover:duration-300 md:text-base md:px-8 md:h-14"
				}, a.createElement(m, {
					propKey: "secondaryButton_textAttr"
				}, s.textAttr)))), a.createElement("div", {
					className: "w-full flex flex-col gap-16"
				}, a.createElement("div", {
					className: "w-full flex flex-col gap-6"
				}, a.createElement(b.svg, {
					className: "w-16 h-auto fill-[#4CAF50]",
					viewBox: "0 0 113 62",
					fill: "none",
					xmlns: "http://www.w3.org/2000/svg"
				}, a.createElement(b.path, {
					initial: {
						offsetPath: 0
					},
					whileInView: {
						offsetPath: 1
					},
					"fill-rule": "evenodd",
					"clip-rule": "evenodd",
					d: "M13.7472 16.0139C8.53124 20.2605 4.96284 24.6492 3.89742 27.8455C3.54813 28.8934 2.41549 29.4597 1.3676 29.1104C0.319715 28.7611 -0.246607 27.6285 0.102686 26.5806C1.53727 22.2768 5.84387 17.2904 11.2217 12.912C16.6724 8.4742 23.5363 4.40599 30.4873 2.06733C37.3947 -0.256691 44.6927 -0.969844 50.7732 1.76375C55.9527 4.09228 59.8821 8.74742 61.9572 16.1338C68.6552 15.0027 74.7486 15.4331 80.0992 17.03C92.7803 20.8147 100.936 31.081 102.598 41.6704L112.357 44.9792L95.0001 60.2128L90.4859 37.5643L98.2134 40.1841C96.105 31.875 89.3092 23.9531 78.9553 20.8629C74.3201 19.4796 68.9101 19.0522 62.8354 20.0449C62.8848 20.3296 62.9319 20.6175 62.9767 20.9088C64.6088 31.5171 59.7361 40.4452 52.7496 47.0562C45.781 53.6504 36.5202 58.1468 28.7198 60.0319C24.8309 60.9718 21.1474 61.3014 18.2108 60.8127C15.3216 60.3319 12.5611 58.9131 11.8083 55.8642C11.115 53.0562 12.4051 49.7397 15.0321 46.1347C17.7326 42.4287 22.1791 37.9589 28.7433 32.657C39.2679 24.1564 49.1299 19.2043 58.0357 16.9602C56.1976 10.614 52.9326 7.12015 49.1331 5.41203C44.3698 3.27064 38.2303 3.6825 31.7628 5.8585C25.3388 8.01987 18.8902 11.8267 13.7472 16.0139ZM58.9175 20.8635C50.6935 22.9493 41.3933 27.5815 31.2567 35.7688C24.8209 40.9669 20.6737 45.1846 18.2648 48.4904C15.7824 51.8971 15.4475 53.9165 15.6917 54.9054C15.8764 55.6533 16.6159 56.4923 18.8674 56.867C21.0713 57.2338 24.1691 57.0166 27.7802 56.1439C34.9798 54.404 43.594 50.2129 50.0004 44.1508C56.3889 38.1056 60.3912 30.4087 59.0233 21.517C58.9894 21.2966 58.9541 21.0788 58.9175 20.8635Z"
				})), a.createElement("p", {
					className: "DESC text-slate-600 dark:text-slate-400"
				}, a.createElement(m, {
					propKey: "description"
				}, t))), a.createElement("ul", {
					className: "flex gap-8 items-center md:mt-6"
				}, l.map((e, t) => a.createElement("li", {
					key: t,
					className: "flex gap-2.5 items-center"
				}, a.createElement(p, {
					propKey: `features_${t}_icon`,
					icon: e.icon,
					iconLibrary: "FontAwesome",
					className: "text-xl text-[#4CAF50]"
				}), a.createElement("label", {
					className: "TEXT-CONTENT text-slate-900 uppercase dark:text-slate-50 md:text-xl"
				}, a.createElement(m, {
					propKey: `features_${t}_text`
				}, e.text))))))), a.createElement("div", {
					className: "relative w-full"
				}, a.createElement("div", {
					className: "absolute z-10 left-0 bg-gradient-to-r from-white w-1/4 h-full dark:from-black"
				}), a.createElement(f, {
					autoFill: "true",
					speed: 50
				}, a.createElement("div", {
					className: "w-full h-auto flex items-center"
				}, o.map((e, t) => a.createElement("div", {
					className: "IMAGE w-80 h-56 mx-3 rounded-xl md:h-60"
				}, a.createElement(c, {
					isFCP: !0,
					key: t,
					propKey: `images_${t}`,
					className: "w-full h-full object-cover rounded-xl bg-slate-200 dark:bg-slate-700 aspect-[4/3]",
					src: e,
					alt: `images_${t}`
				}))))), a.createElement("div", {
					className: " absolute z-10 top-0 right-0 bg-gradient-to-l from-white w-1/4 h-full dark:from-black"
				}))))
			}, {
				title: "Mejora tu Comunicación Empresarial",
				description: "Descubre cómo nuestra aplicación transforma la manera en que tu empresa se comunica a través de WhatsApp, asegurando eficiencia y resultados.",
				images: ["https://message-business.gijac.com/img/deepseek.jpeg", "https://message-business.gijac.com/img/Meta-1.jpg", "https://message-business.gijac.com/img/WHATSAPP.png"],
				primaryButton: {
					textAttr: "text=Contáctanos&link=/contact"
				},
				secondaryButton: {
					textAttr: "text=Más Información&link=/info"
				},
				features: [{
					icon: "fa-solid fa-comments",
					text: "Comunicación Instantánea"
				}, {
					icon: "fa-solid fa-shield-alt",
					text: "Seguridad Garantizada"
				}, {
					icon: "fa-solid fa-chart-line",
					text: "Resultados Medibles"
				}],
				contactInfo: {},
				key: "3Ypgnq70gMCRidxX9-fXV"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "LmFi24tojsYilw7S2TkcK"
			}, a.createElement(function({
				title: e = "Join Our Design Studio",
				description: t = "Explore a career with us. Dive into a creative environment that nurtures your talents and skills.",
				stats: o = [{
					name: "Global Offices",
					value: "15+"
				}, {
					name: "Team Members",
					value: "500+"
				}, {
					name: "Work Hours",
					value: "Flexible"
				}, {
					name: "Vacation Policy",
					value: "Open"
				}],
				imageUrl: n = "https://source.unsplash.com/random/1200x800/?creative,work"
			}) {
				return a.createElement("div", {
					className: "relative isolate overflow-hidden bg-gray-900 py-24 sm:py-32"
				}, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE absolute inset-0 opacity-20 -z-10 h-full w-full object-cover object-right md:object-center rounded-lg bg-slate-100",
					src: n,
					alt: "Design Studio"
				}), a.createElement("div", {
					className: "mx-auto max-w-7xl px-6 lg:px-8"
				}, a.createElement("div", {
					className: "mx-auto max-w-2xl lg:mx-0"
				}, a.createElement("h2", {
					className: "TITLE-PRIMARY text-4xl font-bold tracking-tight text-white sm:text-6xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC mt-6 text-lg leading-8 text-gray-300"
				}, a.createElement(m, {
					propKey: "description"
				}, t))), a.createElement("div", {
					className: "mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none"
				}, a.createElement("dl", {
					className: "mt-16 grid grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 lg:grid-cols-4"
				}, o.map((e, t) => a.createElement("div", {
					key: e.name,
					className: "flex flex-col-reverse"
				}, a.createElement("dt", {
					className: "TEXT-CONTENT text-base leading-7 text-gray-300"
				}, a.createElement(m, {
					propKey: `stats_${t}_name`
				}, e.name)), a.createElement("dd", {
					className: "text-2xl font-bold leading-9 tracking-tight text-white"
				}, a.createElement(m, {
					propKey: `stats_${t}_value`
				}, e.value))))))))
			}, {
				title: "Bienvenido a Tu Solución Ideal",
				description: "Explora cómo podemos mejorar la comunicación de tu empresa con soluciones personalizadas.",
				stats: [{
					name: "Empresas Satisfechas",
					value: "200+"
				}, {
					name: "Mensajes Gestionados",
					value: "1M+"
				}, {
					name: "Integraciones",
					value: "Fáciles"
				}, {
					name: "Soporte",
					value: "24/7"
				}],
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/bf184ded-f84a-40db-9998-0e93d6bc215d.jpeg?oldPrompt=La imagen representa un menú interactivo destacado en la parte superior del sitio web, 'Tu Solución Ideal', diseñado para facilitar la navegación entre las diferentes opciones disponibles. El menú debe ser claro y accesible, reflejando la temática empresarial del sitio, enfocándose en la comunicación a través de WhatsApp. Se incluye iconografía relevante que simbolice la mejora en la comunicación empresarial, así como elementos que sugieran dinamismo y conectividad. La imagen muestra cómo los usuarios pueden interactuar con el menú para explorar varias secciones del sitio, capturando un sentido de fluidez y facilidad de uso.",
				key: "LmFi24tojsYilw7S2TkcK"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "ME0o4SNIWCXoq7LPhu3ky"
			}, a.createElement(function({
				locationName: e = "New York",
				title: t = "Design Studio Location",
				description: o = "Explore our workspace through the interactive map below and get a feel for the creative environment where we bring innovative designs to life.",
				locationIcon: n = "fa-solid fa-location-dot"
			}) {
				const s = `https://maps.google.com/maps?width=100%&height=600&hl=en&q=${encodeURIComponent(e)}&ie=UTF8&t=&z=14&iwloc=B&output=embed`;
				return a.createElement("div", {
					className: "relative w-full"
				}, a.createElement("div", {
					className: "max-w-7xl mx-auto py-28 px-4 "
				}, a.createElement("div", {
					className: "absolute inset-0 w-full h-200"
				}, a.createElement("iframe", {
					className: "w-full h-full",
					src: s,
					style: {
						filter: "grayscale(1) contrast(1.2)",
						opacity: .4
					},
					allowFullScreen: !0,
					loading: "lazy",
					title: `Map of ${e}`,
					scrolling: "no"
				})), a.createElement("div", {
					className: "w-fit md:w-1/2 gap-8 bg-white/70 dark:bg-black/70 shadow backdrop-blur rounded-2xl py-10 px-10"
				}, a.createElement("h1", {
					className: "TITLE-PRIMARY text-3xl font-semibold text-slate-900 dark:text-white mb-4"
				}, a.createElement(m, {
					propKey: "title"
				}, t)), a.createElement("p", {
					className: "DESC text-base font-normal text-slate-700 dark:text-white/70 mb-4"
				}, a.createElement(m, {
					propKey: "description"
				}, o)), a.createElement("p", {
					className: "flex items-center text-sky-600"
				}, a.createElement(p, {
					propKey: "locationIcon",
					icon: n,
					iconLibrary: "FontAwesome",
					className: "TEXT-LINK text-base text-sky-600 mr-2"
				}), a.createElement(m, {
					propKey: "locationName"
				}, e)))))
			}, {
				locationName: "Madrid",
				title: "Áreas de Uso",
				description: "Descubre las regiones donde nuestra aplicación está transformando la comunicación empresarial.",
				locationIcon: "fa-solid fa-map-marker-alt",
				key: "ME0o4SNIWCXoq7LPhu3ky"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "XCixlt9uiSmSv1iTER-gJ"
			}, a.createElement(function({
				title: e = "Trusted by the world's most innovative design teams",
				logos: t = ["https://www.uilogos.co/logos/type/acme-black.png", "https://www.uilogos.co/logos/type/aven-black.png", "https://www.uilogos.co/logos/type/fox-hub-black.png", "https://www.uilogos.co/logos/type/goldline-black.png", "https://www.uilogos.co/logos/type/muzica-black.png"]
			}) {
				return a.createElement("div", {
					className: "bg-white py-16 sm:py-20 dark:bg-slate-800"
				}, a.createElement("div", {
					className: "mx-auto max-w-7xl px-6 lg:px-8"
				}, a.createElement("h2", {
					className: "TITLE-PRIMARY text-center text-lg font-semibold leading-8 text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("div", {
					className: "mx-auto mt-10 flex flex-wrap justify-center items-center gap-x-8 gap-y-10 sm:gap-x-10"
				}, t.map((e, t) => a.createElement(r, {
					type: "rise",
					key: `logo_${t}`
				}, a.createElement(c, {
					propKey: `logos_${t}`,
					className: "IMAGE max-h-12 w-full object-contain aspect-[158/48]",
					src: e,
					alt: `logo_${t}`
				}))))))
			}, {
				title: "Confiado por Marcas Líderes",
				logos: ["https://www.uilogos.co/logos/type/acme-black.png", "https://www.uilogos.co/logos/type/aven-black.png", "https://www.uilogos.co/logos/type/fox-hub-black.png", "https://www.uilogos.co/logos/type/goldline-black.png", "https://www.uilogos.co/logos/type/muzica-black.png"],
				key: "XCixlt9uiSmSv1iTER-gJ"
			}));
			t.push(e)
		} catch (e) {}
		try {
			function v({
				title: e = "Get in Touch with Our Design Studio",
				description: t = "Our team is eager to discuss your design needs and help bring your creative visions to life with our professional expertise.",
				imageUrl: o = "https://source.unsplash.com/1000x800/?office,communication",
				officeHours: n = [{
					region: "USA Office Hours",
					hours: "Monday-Friday\n8:00 am to 5:00 pm"
				}, {
					region: "Canada Office",
					hours: "Monday-Friday\n9:00 am to 6:00 pm"
				}],
				address: s = {
					title: "Our Address",
					label: "8502 Preston Rd. Ingle, Maine 98380, USA"
				},
				contact: l = {
					title: "Get in touch",
					label: ["+1-246-888-0653", "+1-222-632-0194"]
				}
			}) {
				return a.createElement("section", {
					className: "py-10 bg-white dark:bg-slate-800 sm:py-16 lg:py-20 xl:py-24"
				}, a.createElement("div", {
					className: "max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
				}, a.createElement("div", {
					className: "grid grid-cols-1 lg:grid-cols-2 gap-y-8 gap-x-36"
				}, a.createElement("div", null, a.createElement("h2", {
					className: "TITLE-PRIMARY text-4xl font-semibold text-slate-900 dark:text-slate-50 md:text-6xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC mt-6 text-base font-normal text-slate-700 dark:text-slate-300 "
				}, a.createElement(m, {
					propKey: "description"
				}, t))), a.createElement(r, {
					type: "rise"
				}, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE rounded-lg bg-slate-100 w-[37.5rem] h-[25rem] object-cover aspect-[3/2]",
					src: o,
					alt: o
				}))), a.createElement("div", {
					className: "grid grid-cols-1 mt-12 sm:grid-cols-2 lg:grid-cols-4 xl:gap-20 gap-6 sm:gap-10 sm:mt-16 lg:mt-20"
				}, n.map((e, t) => a.createElement("div", {
					key: t
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: `officeHours_${t}_region`
				}, e.region)), a.createElement("p", {
					className: "TEXT-CONTENT mt-5 text-base font-medium text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: `officeHours_${t}_hours`
				}, e.hours)))), a.createElement("div", {
					className: "flex flex-col gap-5"
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: "address_title"
				}, s.title)), a.createElement("p", {
					className: "TEXT-CONTENT text-base font-medium text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: "address_label"
				}, s.label))), a.createElement("div", {
					className: "flex flex-col gap-5"
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: "contact_title"
				}, l.title)), a.createElement("p", {
					className: "TEXT-CONTENT  text-base font-medium text-slate-900 dark:text-slate-200"
				}, l.label.map((e, t) => a.createElement(a.Fragment, null, a.createElement(m, {
					propKey: `contact_label_${t}`,
					key: t
				}, e))))))))
			}
			const e = a.createElement(o, {
				key: "sgpbnD7STj1qF7AAcDsjl"
			}, a.createElement(v, {
				title: "Ponte en Contacto con Nosotros",
				description: "Nuestro equipo está listo para ayudarte a mejorar la comunicación de tu empresa. Contáctanos para más información.",
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/8fd489ca-68da-4707-b6fc-227421ebf0d8.jpeg?oldPrompt=La imagen debe representar un concepto de conectividad y comunicación empresarial efectiva, enfatizando el uso de WhatsApp como herramienta clave. Debe incluir elementos visuales que sugieran interacciones fluidas y accesibilidad fácil, como iconos de mensajes o dispositivos móviles. El fondo puede sugerir un entorno empresarial profesional, reforzando la idea de eficiencia y modernidad. La escena debe transmitir una atmósfera de confianza y profesionalismo, alineada con los beneficios que ofrece la aplicación presentada en la página principal de Tu Solución Ideal.",
				officeHours: [{
					region: "Horario de España",
					hours: "Lunes-Viernes\n9:00 am a 6:00 pm"
				}, {
					region: "Horario de México",
					hours: "Lunes-Viernes\n8:00 am a 5:00 pm"
				}],
				address: {
					title: "Nuestra Dirección",
					label: "Calle de Ejemplo, 123, Madrid, España"
				},
				contact: {
					title: "Contáctanos",
					label: ["+34-123-456-789", "+52-987-654-321"]
				},
				key: "sgpbnD7STj1qF7AAcDsjl"
			}));
			t.push(e)
		} catch (e) {}
		e.push({
			path: "inicio",
			element: a.createElement(w, null, t)
		})
	}


// ------------------------------------------- precios ----------------------------------------


	{
		const t = [];
		try {
			const e = a.createElement(o, {
				key: "LDAs35rPzn7pnj3PzJOeZ"
			}, a.createElement(function({
				tagline: e = "Empowering Creativity",
				title: t = "Design Studio Services",
				description: o = "Our team is dedicated to offering tailored design solutions. Embrace the power of creativity with us and bring your vision to life."
			}) {
				return a.createElement("div", {
					className: "bg-white py-24 sm:py-32 relative dark:bg-slate-800"
				}, a.createElement("div", {
					className: "mx-auto max-w-7xl px-6 lg:px-8 relative"
				}, a.createElement("div", {
					className: "mx-auto max-w-2xl lg:mx-0"
				}, a.createElement("p", {
					className: "DESC text-base font-semibold leading-7 text-sky-600 dark:text-sky-300"
				}, a.createElement(m, {
					propKey: "tagline"
				}, e)), a.createElement("h2", {
					className: "TITLE-PRIMARY mt-2 text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-50 sm:text-6xl"
				}, a.createElement(m, {
					propKey: "title"
				}, t)), a.createElement("p", {
					className: "DESC mt-6 text-lg leading-8 text-slate-700 dark:text-slate-300"
				}, a.createElement(m, {
					propKey: "description"
				}, o)))))
			}, {
				tagline: "Comunicación Empresarial Eficiente",
				title: "Planes de Comunicación Empresarial",
				description: "Descubre cómo nuestros servicios pueden transformar la manera en que tu empresa se comunica a través de WhatsApp, mejorando la eficiencia y la relación con tus clientes.",
				key: "LDAs35rPzn7pnj3PzJOeZ"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "Ii9P0ha9oL7iookwEJ40F"
			}, a.createElement(function({
				title: e = "Flexible Plans for Your Design Needs",
				description: t = "Choose the perfect plan that fits your project requirements. Enjoy high-quality design solutions with our dedicated support.",
				plans: o = [{
					name: "Basic",
					price: "$0",
					period: "per month",
					features: [{
						text: "Access to Basic Templates",
						icon: "fa-solid fa-check"
					}, {
						text: "5 Projects",
						icon: "fa-solid fa-check"
					}, {
						text: "Community Support",
						icon: "fa-solid fa-check"
					}],
					buttonTextAttr: "text=Start 14 Days Trial&link=/",
					isPopular: !1
				}, {
					name: "Standard",
					price: "$29",
					period: "per month",
					features: [{
						text: "Premium Template Access",
						icon: "fa-solid fa-check"
					}, {
						text: "Unlimited Projects",
						icon: "fa-solid fa-check"
					}, {
						text: "Priority Support",
						icon: "fa-solid fa-check"
					}],
					featureIcon: "fa-solid fa-check",
					buttonTextAttr: "text=Start 14 Days Trial&link=/",
					isPopular: !0
				}, {
					name: "Pro",
					price: "$49",
					period: "per month",
					features: [{
						text: "Advance Design Tools",
						icon: "fa-solid fa-check"
					}, {
						text: "Collaborative Projects",
						icon: "fa-solid fa-check"
					}, {
						text: "24/7 Support",
						icon: "fa-solid fa-check"
					}],
					featureIcon: "fa-solid fa-check",
					buttonTextAttr: "text=Start 14 Days Trial&link=/",
					isPopular: !1
				}],
				linkText: n = "No credit card required",
				link: s = "#"
			}) {
				return a.createElement("section", {
					className: "py-10 bg-white dark:bg-slate-800"
				}, a.createElement("div", {
					className: "px-4 py-10 mx-auto max-w-7xl flex flex-col gap-16"
				}, a.createElement(r, {
					type: "rise"
				}, a.createElement("div", {
					className: "flex flex-col gap-6 text-center"
				}, a.createElement("h2", {
					className: "TITLE-PRIMARY text-4xl font-semibold text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC text-base font-normal text-slate-600 dark:text-slate-300"
				}, a.createElement(m, {
					propKey: "description"
				}, t)))), a.createElement("div", {
					className: "grid grid-cols-1 gap-5 text-center md:grid-cols-3"
				}, o.map((e, t) => a.createElement(r, {
					type: "rise",
					key: t
				}, a.createElement("div", {
					className: `w-full h-full p-6 flex flex-col justify-between rounded-2xl outline outline-offset-0 hover:outline-sky-400 active:outline-sky-500 focus:outline-sky-500 dark:hover:outline-sky-400 dark:active:outline-sky-500 dark:focus:outline-sky-500 ${e.isPopular ? "outline-sky-500" : "outline-slate-200 dark:outline-slate-700"}`
				}, a.createElement("div", null, a.createElement("h3", {
					className: "TITLE-SECONDARY text-lg font-semibold text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: `plans_${t}_name`
				}, e.name)), a.createElement("p", {
					className: "mt-4 text-5xl font-semibold text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: `plans_${t}_price`
				}, e.price)), a.createElement("p", {
					className: "mt-1 text-sm font-normal text-slate-500 dark:text-slate-400"
				}, a.createElement(m, {
					propKey: `plans_${t}_period`
				}, e.period)), a.createElement("ul", {
					className: "mt-8 space-y-4 text-base font-normal text-left text-slate-900 dark:text-slate-200"
				}, e.features.map((e, o) => a.createElement("li", {
					className: "flex items-center gap-2.5",
					key: o
				}, a.createElement(p, {
					propKey: `plans_${t}_features_${o}_icon`,
					icon: e.icon,
					iconLibrary: "FontAwesome",
					className: "text-sky-500 text-base"
				}), a.createElement(m, {
					propKey: `plans_${t}_features_${o}_text`
				}, e.text))))), a.createElement("div", {
					className: "mt-8 flex flex-col gap-4"
				}, a.createElement(d, {
					className: "BTN-PRIMARY inline-flex items-center justify-center text-white bg-sky-500 font-medium border-0 py-2 xl:py-3 px-6 focus:outline-none hover:bg-sky-400 rounded-lg text-sm sm:text-base 2xl:text-lg transition-colors duration-500"
				}, a.createElement(m, {
					propKey: `plans_${t}_buttonTextAttr`
				}, e.buttonTextAttr)), a.createElement("a", {
					href: s,
					className: "TEXT-LINK text-sm font-normal text-slate-500 dark:text-slate-400 active:text-slate-600"
				}, a.createElement(m, {
					propKey: "linkText"
				}, n)))))))))
			}, {
				title: "Planes Flexibles para tus Necesidades",
				description: "Selecciona el plan que mejor se adapte a tus necesidades empresariales. Mejora la comunicación y obtén soporte dedicado con nuestras soluciones.",
				plans: [{
					name: "Básico",
					price: "€0",
					period: "por mes",
					features: [{
						text: "Acceso a Plantillas Básicas",
						icon: "fa-solid fa-check"
					}, {
						text: "5 Conversaciones Simultáneas",
						icon: "fa-solid fa-check"
					}, {
						text: "Soporte Comunitario",
						icon: "fa-solid fa-check"
					}],
					buttonTextAttr: "text=Comienza Prueba de 14 Días&link=inicio",
					isPopular: !1
				}, {
					name: "Estándar",
					price: "€29",
					period: "por mes",
					features: [{
						text: "Acceso a Plantillas Premium",
						icon: "fa-solid fa-check"
					}, {
						text: "Conversaciones Ilimitadas",
						icon: "fa-solid fa-check"
					}, {
						text: "Soporte Prioritario",
						icon: "fa-solid fa-check"
					}],
					buttonTextAttr: "text=Comienza Prueba de 14 Días&link=inicio",
					isPopular: !0
				}, {
					name: "Pro",
					price: "€49",
					period: "por mes",
					features: [{
						text: "Herramientas Avanzadas de Comunicación",
						icon: "fa-solid fa-check"
					}, {
						text: "Proyectos Colaborativos",
						icon: "fa-solid fa-check"
					}, {
						text: "Soporte 24/7",
						icon: "fa-solid fa-check"
					}],
					buttonTextAttr: "text=Comienza Prueba de 14 Días&link=inicio",
					isPopular: !1
				}],
				linkText: "No se requiere tarjeta de crédito",
				link: "#",
				key: "Ii9P0ha9oL7iookwEJ40F"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "ZYZLykJW_2DEQr_jLM06B"
			}, a.createElement(function({
				title: e = "Packages",
				description: t = "Meet the creative minds behind our successful projects.",
				tr: o = ["Package", "Project", "Detail", "Price"],
				data: n = [{
					imgUrl: "https://source.unsplash.com/600x600/?branding,logo",
					col1: "Starter Package",
					col2: "Logo & Brand Identity",
					col3: "Up to 3 Concepts",
					col4: "5 Revisions",
					col5: "$499"
				}, {
					imgUrl: "https://source.unsplash.com/600x600/?website,design",
					col1: "Professional Website",
					col2: "Responsive Design",
					col3: "Up to 10 Pages",
					col4: "SEO & Analytics Setup",
					col5: "$2,999"
				}, {
					imgUrl: "https://source.unsplash.com/600x600/?mobile,app",
					col1: "Mobile App Design",
					col2: "iOS & Android",
					col3: "User Interface Design",
					col4: "User Experience Review",
					col5: "$4,999"
				}]
			}) {
				return a.createElement("div", {
					className: "w-full max-w-7xl mx-auto py-10 px-4"
				}, a.createElement("div", {
					className: "max-w-lg"
				}, a.createElement("h3", {
					className: "TITLE-PRIMARY text-4xl font-extrabold text-lg sm:text-2xl dark:text-white/80"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC text-slate-700 mt-2 text-base dark:text-white/70"
				}, a.createElement(m, {
					propKey: "description"
				}, t))), a.createElement("div", {
					className: "mt-8 shadow-sm border border-black/10 dark:border-white/10 rounded-lg overflow-x-auto"
				}, a.createElement("table", {
					className: "w-full table-auto text-sm text-left"
				}, a.createElement("thead", {
					className: "bg-white dark:bg-slate-800 text-slate-900 dark:text-white/80 font-medium border-b border-black/10 dark:border-white/10"
				}, a.createElement("tr", null, o.map((e, t) => a.createElement("th", {
					key: t,
					className: "py-3 px-6"
				}, a.createElement(m, {
					propKey: `tr_${t}`
				}, e))))), a.createElement("tbody", {
					className: "text-slate-900 dark:text-white/70 divide-y divide-black/10 dark:divide-white/10"
				}, n.map((e, t) => a.createElement("tr", {
					key: t
				}, a.createElement("td", {
					className: "flex items-center gap-x-3 py-3 px-6 whitespace-nowrap"
				}, a.createElement(c, {
					propKey: `data_${t}_imgUrl`,
					className: "IMAGE w-10 h-10 rounded-full bg-slate-100 object-cover aspect-[1/1]",
					src: e.imgUrl,
					alt: e.imgUrl
				}), a.createElement("div", null, a.createElement("span", {
					className: "block text-slate-900 dark:text-white/80 text-sm font-medium"
				}, a.createElement(m, {
					propKey: `data_${t}_col1`
				}, e.col1)), a.createElement("span", {
					className: "block text-slate-700 dark:text-white/70 text-xs"
				}, a.createElement(m, {
					propKey: `data_${t}_col2`
				}, e.col2)))), a.createElement("td", {
					className: "px-6 py-4 whitespace-nowrap"
				}, a.createElement(m, {
					propKey: `data_${t}_col3`
				}, e.col3)), a.createElement("td", {
					className: "px-6 py-4 whitespace-nowrap"
				}, a.createElement(m, {
					propKey: `data_${t}_col4`
				}, e.col4)), a.createElement("td", {
					className: "px-6 py-4 whitespace-nowrap"
				}, a.createElement(m, {
					propKey: `data_${t}_col5`
				}, e.col5))))))))
			}, {
				title: "Comparativa de Planes",
				description: "Evalúa las características de cada plan y elige el que mejor se ajuste a tus necesidades de comunicación empresarial.",
				tr: ["Plan", "Características", "Detalles", "Precio"],
				data: [{
					imgUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/e9174bff-99e0-40dd-9bf6-b9e268e236c8.jpeg?oldPrompt=Una imagen que representa un escenario de negocio donde se está utilizando WhatsApp para mejorar la comunicación empresarial. La imagen debe mostrar a un grupo de personas trabajando en una oficina moderna, con atención en la interacción digital a través de dispositivos móviles. Esto subraya la importancia de la comunicación eficiente dentro de un entorno profesional.",
					col1: "Plan Inicial",
					col2: "Identidad de Marca",
					col3: "Hasta 3 Conceptos",
					col4: "5 Revisiones",
					col5: "€499"
				}, {
					imgUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/f3a3aa3a-cc06-4324-9097-96f6ec8c7384.jpeg?oldPrompt=Una escena que ilustre el concepto de personalización y adaptación de planes de comunicación empresarial. La imagen debe mostrar una variedad de opciones disponibles para los usuarios, destacando la flexibilidad y adaptabilidad de los planes ofrecidos, con elementos visuales como engranajes o piezas de rompecabezas que simbolicen la construcción de una solución a medida.",
					col1: "Plan Profesional",
					col2: "Diseño Responsivo",
					col3: "Hasta 10 Páginas",
					col4: "SEO & Configuración de Analíticas",
					col5: "€2,999"
				}, {
					imgUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/68ff1460-e2d1-4c80-b3a4-b21aecbdf169.jpeg?oldPrompt=Una representación visual de una tabla comparativa donde se destacan diferentes planes de comunicación empresarial a través de WhatsApp. La imagen debe incluir elementos que simbolicen las características claves de cada plan, como iconos de conectividad, mensajería y atención al cliente, para ayudar a los usuarios a visualizar sus opciones de manera clara y efectiva.",
					col1: "Diseño de App Móvil",
					col2: "iOS & Android",
					col3: "Diseño de Interfaz de Usuario",
					col4: "Revisión de Experiencia de Usuario",
					col5: "€4,999"
				}],
				key: "ZYZLykJW_2DEQr_jLM06B"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "1bFLZZiLUmc8JBX_OsQeP"
			}, a.createElement(function({
				sectionTitle: e = "Design Studio Benefits",
				sectionSubtitle: t = "Explore the Advantages",
				sectionDescription: o = "Elevate your brand's digital narrative with our cutting-edge design solutions.",
				features: n = [{
					title: "Enhance Visual Appeal",
					description: "Create stunning visuals that captivate your audience and make your brand unforgettable.",
					icon: "fa-solid fa-eye"
				}, {
					title: "Build Brand Recognition",
					description: "Consistent and memorable design elements that resonate with your audience and foster brand loyalty.",
					icon: "fa-solid fa-star"
				}, {
					title: "Increase Engagement",
					description: "Engaging designs that improve user experience and drive higher interaction rates.",
					icon: "fa-solid fa-users"
				}]
			}) {
				return a.createElement("section", {
					className: "w-full max-w-7xl mx-auto py-10 px-4 dark:bg-slate-900"
				}, a.createElement("div", {
					className: "container space-y-10 md:space-y-16"
				}, a.createElement("header", {
					className: "flex flex-col items-start"
				}, a.createElement("p", {
					className: "TITLE-SECONDARY md:text-xl font-semibold text-sky-500 dark:text-sky-300"
				}, a.createElement(m, {
					propKey: "sectionTitle"
				}, e)), a.createElement("h2", {
					className: "TITLE-PRIMARY max-w-4xl mt-6 text-3xl font-semibold md:text-4xl lg:text-4xl text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: "sectionSubtitle"
				}, t), a.createElement("span", {
					className: "DESC block text-slate-500"
				}, a.createElement(m, {
					propKey: "sectionDescription"
				}, o)))), a.createElement("ul", {
					className: "grid w-full gap-12 md:grid-cols-3"
				}, n.map((e, t) => a.createElement("li", {
					key: `features_${t}`,
					className: "w-full"
				}, a.createElement("div", {
					className: "flex items-center justify-center w-12 h-12 bg-white shadow-lg text-sky-500 rounded-xl ring-1 ring-slate-200 dark:ring-slate-700 shadow-slate-500/10"
				}, a.createElement(p, {
					propKey: `features_${t}_icon`,
					icon: e.icon,
					iconLibrary: "FontAwesome",
					className: "text-xl text-sky-500 drop-shadow"
				})), a.createElement("h3", {
					className: "TITLE-SECONDARY mt-6 text-2xl font-semibold  text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: `features_${t}_title`
				}, e.title)), a.createElement("p", {
					className: "DESC mt-1.5 text-slate-700 dark:text-slate-300 text-lg"
				}, a.createElement(m, {
					propKey: `features_${t}_description`
				}, e.description)))))))
			}, {
				sectionTitle: "Beneficios de Nuestros Planes",
				sectionSubtitle: "Explora las Ventajas",
				sectionDescription: "Mejora la narrativa digital de tu marca con nuestras soluciones de comunicación innovadoras.",
				features: [{
					title: "Mejora la Comunicación Visual",
					description: "Crea visuales impactantes que cautiven a tu audiencia y hagan tu marca inolvidable.",
					icon: "fa-solid fa-eye"
				}, {
					title: "Fomenta el Reconocimiento de Marca",
					description: "Elementos de diseño consistentes y memorables que resuenen con tu audiencia y fomenten la lealtad a la marca.",
					icon: "fa-solid fa-star"
				}, {
					title: "Incrementa el Compromiso",
					description: "Diseños atractivos que mejoran la experiencia del usuario y aumentan las tasas de interacción.",
					icon: "fa-solid fa-users"
				}],
				key: "1bFLZZiLUmc8JBX_OsQeP"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "H559pbgPrKJK71H9vCdNU"
			}, a.createElement(function({
				testimonial: e = "Commodo amet fugiat excepteur sunt qui ea elit cupidatat ullamco consectetur ipsum elit consequat. Elit sunt proident ea nulla ad nulla dolore ad pariatur tempor non. Sint veniam minim et ea.",
				imageUrl: t = "https://source.unsplash.com/random/976x976/?portrait,professional",
				authorName: o = "Judith Black",
				authorRole: n = "CEO of Design Studio"
			}) {
				return a.createElement("section", {
					className: "isolate overflow-hidden bg-white dark:bg-slate-800 px-6 lg:px-8"
				}, a.createElement("div", {
					className: "relative mx-auto max-w-2xl py-16 sm:py-20 lg:max-w-4xl"
				}, a.createElement("div", {
					className: "absolute left-1/2 top-0 -z-10 h-[50rem] w-[90rem] -translate-x-1/2 bg-[radial-gradient(50%_100%_at_top,theme(colors.sky.100),transparent)] opacity-20 lg:left-36"
				}), a.createElement("div", {
					className: "absolute inset-y-0 right-1/2 -z-10 mr-12 w-[150vw] origin-bottom-left skew-x-[-30deg] bg-white dark:bg-slate-700 shadow-xl shadow-sky-600/10 ring-1 ring-sky-50 dark:ring-slate-700 sm:mr-20 md:mr-0 lg:right-full lg:-mr-36 lg:origin-center"
				}), a.createElement("figure", {
					className: "grid grid-cols-1 items-center gap-x-6 gap-y-8 lg:gap-x-10"
				}, a.createElement("div", {
					className: "relative col-span-2 lg:col-start-1 lg:row-start-2"
				}, a.createElement("svg", {
					viewBox: "0 0 162 128",
					fill: "none",
					"aria-hidden": "true",
					class: "absolute -top-12 left-0 -z-10 h-32 stroke-gray-900/10 dark:stroke-gray-100/10"
				}, a.createElement("path", {
					id: "b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb",
					d: "M65.5697 118.507L65.8918 118.89C68.9503 116.314 71.367 113.253 73.1386 109.71C74.9162 106.155 75.8027 102.28 75.8027 98.0919C75.8027 94.237 75.16 90.6155 73.8708 87.2314C72.5851 83.8565 70.8137 80.9533 68.553 78.5292C66.4529 76.1079 63.9476 74.2482 61.0407 72.9536C58.2795 71.4949 55.276 70.767 52.0386 70.767C48.9935 70.767 46.4686 71.1668 44.4872 71.9924L44.4799 71.9955L44.4726 71.9988C42.7101 72.7999 41.1035 73.6831 39.6544 74.6492C38.2407 75.5916 36.8279 76.455 35.4159 77.2394L35.4047 77.2457L35.3938 77.2525C34.2318 77.9787 32.6713 78.3634 30.6736 78.3634C29.0405 78.3634 27.5131 77.2868 26.1274 74.8257C24.7483 72.2185 24.0519 69.2166 24.0519 65.8071C24.0519 60.0311 25.3782 54.4081 28.0373 48.9335C30.703 43.4454 34.3114 38.345 38.8667 33.6325C43.5812 28.761 49.0045 24.5159 55.1389 20.8979C60.1667 18.0071 65.4966 15.6179 71.1291 13.7305C73.8626 12.8145 75.8027 10.2968 75.8027 7.38572C75.8027 3.6497 72.6341 0.62247 68.8814 1.1527C61.1635 2.2432 53.7398 4.41426 46.6119 7.66522C37.5369 11.6459 29.5729 17.0612 22.7236 23.9105C16.0322 30.6019 10.618 38.4859 6.47981 47.558L6.47976 47.558L6.47682 47.5647C2.4901 56.6544 0.5 66.6148 0.5 77.4391C0.5 84.2996 1.61702 90.7679 3.85425 96.8404L3.8558 96.8445C6.08991 102.749 9.12394 108.02 12.959 112.654L12.959 112.654L12.9646 112.661C16.8027 117.138 21.2829 120.739 26.4034 123.459L26.4033 123.459L26.4144 123.465C31.5505 126.033 37.0873 127.316 43.0178 127.316C47.5035 127.316 51.6783 126.595 55.5376 125.148L55.5376 125.148L55.5477 125.144C59.5516 123.542 63.0052 121.456 65.9019 118.881L65.5697 118.507Z"
				}), a.createElement("use", {
					href: "#b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb",
					x: "86"
				})), a.createElement("blockquote", {
					className: "DESC text-xl font-semibold leading-8 text-gray-900 dark:text-white/80 sm:text-2xl sm:leading-9"
				}, a.createElement(m, {
					propKey: "testimonial"
				}, e))), a.createElement("div", {
					className: "col-end-1 w-16 lg:row-span-4 lg:w-72"
				}, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE rounded-xl bg-sky-50 dark:bg-slate-700 lg:rounded-3xl w-full aspect-square object-cover",
					src: t,
					alt: t
				})), a.createElement("figcaption", {
					className: "text-base lg:col-start-1 lg:row-start-3"
				}, a.createElement("div", {
					className: "TITLE-PRIMARY font-semibold text-gray-900 dark:text-white/80"
				}, a.createElement(m, {
					propKey: "authorName"
				}, o)), a.createElement("div", {
					className: "TITLE-SECONDARY mt-1 text-gray-500 dark:text-white/60"
				}, a.createElement(m, {
					propKey: "authorRole"
				}, n))))))
			}, {
				testimonial: "Desde que implementamos los planes de Tu Solución Ideal, nuestra comunicación con los clientes ha mejorado significativamente. La interfaz es intuitiva y el soporte es excepcional.",
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/505482d4-8e44-4a9b-b5c3-f4134cedb30b.jpeg?oldPrompt=La imagen debe mostrar a un cliente satisfecho utilizando un dispositivo móvil, posiblemente interactuando con una aplicación de comunicación empresarial en WhatsApp. El cliente debe parecer confiado y contento, simbolizando éxito y satisfacción con los planes ofrecidos por Tu Solución Ideal. La escena debe incluir un entorno que sugiera un ambiente profesional, como una oficina moderna, para reforzar la credibilidad de los testimonios. Elementos adicionales pueden incluir íconos o gráficos sutiles que representen conexión y comunicación, reflejando el enfoque del sitio web en mejorar la comunicación empresarial.",
				authorName: "Carlos Fernández",
				authorRole: "Gerente de Comunicaciones",
				key: "H559pbgPrKJK71H9vCdNU"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "gEgbH4CAY4YP8QkECALFj"
			}, a.createElement(function({
				introTitle: e = "FAQS",
				title: t = "Frequently Asked Questions",
				faqItems: o = [{
					question: "How do I start a project with your studio?",
					answer: "Starting a project with us is easy! Just contact us through our website or give us a call, and we'll guide you through the process.",
					icon: "fa-solid fa-angle-down"
				}, {
					question: "What kind of design services do you offer?",
					answer: "We offer a wide range of design services including branding, web design, UI/UX, and print materials.",
					icon: "fa-solid fa-angle-down"
				}, {
					question: "Can I see examples of past projects?",
					answer: "Absolutely! Our portfolio is available on our website, showcasing a variety of projects we've completed.",
					icon: "fa-solid fa-angle-down"
				}],
				imageUrl: n = "https://source.unsplash.com/1000x1400/?studio,design"
			}) {
				const [s, l] = a.useState(0);
				return a.createElement("section", {
					className: "py-10 bg-white dark:bg-slate-800"
				}, a.createElement("div", {
					className: "px-4 py-10 mx-auto max-w-7xl"
				}, a.createElement("div", {
					className: "grid grid-cols-1 items-center gap-16 md:grid-cols-2 md:gap-24"
				}, a.createElement(r, {
					type: "rise"
				}, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE object-cover w-full h-auto aspect-[4/3] md:aspect-[3/4] rounded-2xl bg-slate-100",
					src: n,
					alt: n
				})), a.createElement("div", {
					className: "flex flex-col gap-12"
				}, a.createElement(r, {
					type: "rise"
				}, a.createElement("div", {
					className: "flex flex-col gap-6"
				}, a.createElement("p", {
					className: "DESC font-semibold tracking-widest text-sky-500 uppercase"
				}, a.createElement(m, {
					propKey: "introTitle"
				}, e)), a.createElement("h2", {
					className: "TITLE-PRIMARY text-5xl font-semibold text-slate-900 dark:text-white"
				}, a.createElement(m, {
					propKey: "title"
				}, t)))), a.createElement(r, {
					type: "rise"
				}, a.createElement("div", {
					className: "-my-8 divide-y divide-black/10 dark:divide-white/10"
				}, o.map((e, t) => a.createElement("div", {
					key: t,
					className: "py-8"
				}, a.createElement("button", {
					onClick: () => l(s === t ? -1 : t),
					className: "flex items-center justify-between w-full py-6"
				}, a.createElement(m, {
					propKey: `faqItems_${t}_question`,
					className: "TITLE-SECONDARY text-xl font-medium text-left text-slate-900 dark:text-white/90"
				}, e.question), a.createElement(p, {
					propKey: `faqItems_${t}_icon`,
					icon: e.icon,
					iconLibrary: "FontAwesome",
					className: "ml-6 text-lg text-slate-400 dark:text-white/90"
				})), a.createElement("div", {
					className: `${s === t ? "block " : "hidden"}`
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-base text-slate-600 dark:text-white/90"
				}, a.createElement(m, {
					propKey: `faqItems_${t}_answer`
				}, e.answer)))))))))))
			}, {
				introTitle: "PREGUNTAS FRECUENTES",
				title: "Preguntas Frecuentes sobre Nuestros Planes",
				faqItems: [{
					question: "¿Cómo empiezo con un plan?",
					answer: "Iniciar con nuestros planes es sencillo. Contáctanos a través de nuestro sitio web y te guiaremos en cada paso del proceso.",
					icon: "fa-solid fa-angle-down"
				}, {
					question: "¿Qué tipo de soporte ofrecen?",
					answer: "Ofrecemos soporte comunitario, prioritario y 24/7, dependiendo del plan que elijas.",
					icon: "fa-solid fa-angle-down"
				}, {
					question: "¿Puedo ver ejemplos de implementación exitosa?",
					answer: "Por supuesto, visita nuestra sección de Casos de Éxito para explorar cómo nuestros clientes han mejorado su comunicación.",
					icon: "fa-solid fa-angle-down"
				}],
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/be0f2e39-ebe7-4038-b413-05897feedafb.jpeg?oldPrompt=La imagen muestra una escena donde personas están interactuando en un entorno de oficina moderno, simbolizando la mejora en la comunicación empresarial a través de WhatsApp. En el centro, un grupo de personas, que parecen ser colegas, discuten mirando una pantalla que muestra gráficos o tablas, representando opciones de planes y precios. En el fondo, se pueden ver elementos que sugieren un ambiente profesional y colaborativo, como escritorios con ordenadores y herramientas de comunicación modernas. Esta imagen ilustra cómo las soluciones ofrecidas por 'Tu Solución Ideal' facilitan la comunicación eficiente y la toma de decisiones informada sobre los servicios empresariales.",
				key: "gEgbH4CAY4YP8QkECALFj"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "7kMQGSKtXNgnkp5voFeep"
			}, a.createElement(function({
				title: e = "Elevate Your Design Workflow",
				description: t = "Join our creative community and explore all the features we offer. Realize your potential with every project!",
				buttonTextAttr: o = "text=Get Started&link=/",
				learnMoreTextAttr: n = "text=Discover More&link=/",
				learnMoreIcon: s = "fa-solid fa-arrow-right",
				backgroundColor: l = {
					colorA: "#7775D6",
					colorB: "#E935C1"
				}
			}) {
				return a.createElement("div", {
					className: "w-full bg-white dark:bg-slate-800"
				}, a.createElement("div", {
					className: "max-w-7xl mx-auto py-10 px-4"
				}, a.createElement("div", {
					className: "relative isolate overflow-hidden bg-gray-900 px-6 py-24 text-center shadow-2xl sm:rounded-3xl sm:px-16"
				}, a.createElement("h2", {
					className: "TITLE-PRIMARY mx-auto max-w-2xl text-3xl font-bold tracking-tight text-white/90 sm:text-4xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-300/90"
				}, a.createElement(m, {
					propKey: "description"
				}, t)), a.createElement("div", {
					className: "mt-10 flex items-center justify-center gap-x-6"
				}, a.createElement(d, {
					className: "BTN-PRIMARY flex items-center justify-center text-white bg-sky-500 font-medium border-0 py-2 xl:py-3 px-6 focus:outline-none hover:bg-sky-400 rounded-lg text-sm sm:text-base 2xl:text-lg transition-colors duration-500"
				}, a.createElement(m, {
					propKey: "buttonTextAttr"
				}, o)), a.createElement(d, {
					className: "BTN-SECONDARY text-sm group flex items-center gap-1 font-semibold leading-6 text-white"
				}, a.createElement(m, {
					propKey: "learnMoreTextAttr"
				}, n), a.createElement(p, {
					propKey: "learnMoreIcon",
					icon: s,
					iconLibrary: "FontAwesome",
					className: "group-hover:translate-x-1 transition-all duration-300"
				}))), a.createElement("svg", {
					viewBox: "0 0 1024 1024",
					className: "absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]",
					"aria-hidden": "true"
				}, a.createElement("circle", {
					cx: "512",
					cy: "512",
					r: "512",
					fill: "url(#827591b1-ce8c-4110-b064-7cb85a0b1217)",
					fillOpacity: "0.7"
				}), a.createElement("defs", null, a.createElement("radialGradient", {
					id: "827591b1-ce8c-4110-b064-7cb85a0b1217"
				}, a.createElement("stop", {
					stopColor: l.colorA
				}), a.createElement("stop", {
					offset: "1",
					stopColor: l.colorB
				})))))))
			}, {
				title: "Mejora tu Flujo de Comunicación",
				description: "Únete a nuestra comunidad empresarial y explora todas las características que ofrecemos. ¡Realiza tu potencial con cada interacción!",
				buttonTextAttr: "text=Empieza Ahora&link=inicio",
				learnMoreTextAttr: "text=Descubre Más&link=casos-de-exito",
				learnMoreIcon: "fa-solid fa-arrow-right",
				backgroundColor: {
					colorA: "#4CAF50",
					colorB: "#8BC34A"
				},
				key: "7kMQGSKtXNgnkp5voFeep"
			}));
			t.push(e)
		} catch (e) {}
		e.push({
			path: "precios",
			element: a.createElement(w, null, t)
		})
	}

// ---------------------------------------- Casos de exito --------------------------------
	{
		const t = [];
		try {
			const e = a.createElement(o, {
				key: "xMV0uxaKLz6UXYbjSz7Lf"
			}, a.createElement(function({
				features: e = [{
					icon: "fa-solid fa-magnifying-glass",
					title: "Innovative Design Solutions",
					description: "Our team provides modern design solutions that elevate your brand and engage your audience."
				}, {
					icon: "fa-solid fa-lock",
					title: "Secure and Reliable",
					description: "We prioritize security and reliability to ensure your digital assets are protected."
				}, {
					icon: "fa-solid fa-headset",
					title: "Dedicated Support",
					description: "Our customer support team is available 24/7 to assist you with any inquiries."
				}]
			}) {
				return a.createElement("section", {
					className: "py-10 bg-white dark:bg-slate-800 sm:py-16 lg:py-20"
				}, a.createElement("div", {
					className: "max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
				}, a.createElement("div", {
					className: "grid grid-cols-1 text-center gap-y-10 md:grid-cols-3 md:text-left"
				}, e.map((e, t) => a.createElement("div", {
					key: t,
					className: `md:${t > 0 ? "border-l border-black/10 dark:border-white/10" : ""} md:px-6 lg:px-12`
				}, a.createElement(r, {
					type: "rise"
				}, a.createElement(p, {
					propKey: `features_${t}_icon`,
					icon: e.icon,
					iconLibrary: "FontAwesome",
					className: "text-4xl text-slate-900 dark:text-white/80"
				}), a.createElement("h3", {
					className: "TITLE-PRIMARY mt-12 text-lg font-bold text-slate-900 dark:text-white/80"
				}, a.createElement(m, {
					propKey: `features_${t}_title`
				}, e.title)), a.createElement("p", {
					className: "DESC mt-5 text-base text-slate-700 dark:text-white/80"
				}, a.createElement(m, {
					propKey: `features_${t}_description`
				}, e.description))))))))
			}, {
				features: [{
					icon: "fa-solid fa-comments",
					title: "Comunicación Efectiva",
					description: "Nuestros clientes han mejorado la comunicación interna y externa utilizando WhatsApp como canal principal."
				}, {
					icon: "fa-solid fa-chart-line",
					title: "Incremento de Ventas",
					description: "Las soluciones implementadas han llevado a un notable aumento en las conversiones y ventas."
				}, {
					icon: "fa-solid fa-users",
					title: "Fidelización de Clientes",
					description: "Hemos ayudado a empresas a construir relaciones duraderas con sus clientes a través de interacciones personalizadas."
				}],
				key: "xMV0uxaKLz6UXYbjSz7Lf"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "pwPZ1ayFOFJCj1ErHHPzJ"
			}, a.createElement(function({
				introTitle: e = "Process",
				mainTitle: t = "How Design Studio Works for You",
				description: o = "Discover a seamless way to create and manage your design projects. Our platform is built to empower your creative workflow.",
				steps: n = [{
					title: "Create Free Account",
					description: "Start your journey by signing up with us. No credit card required and instant access to our features.",
					imageKeyword: "account"
				}, {
					title: "Add Team Members",
					description: "Collaborate with your team by adding them to your workspace. Simplify your workflow and increase productivity.",
					imageKeyword: "team"
				}, {
					title: "Start Automation",
					description: "Implement automation tools and watch your efficiency skyrocket. Say goodbye to repetitive tasks.",
					imageKeyword: "automation"
				}]
			}) {
				return a.createElement("section", {
					className: "py-10 bg-slate-50 dark:bg-slate-800"
				}, a.createElement("div", {
					className: "px-4 mx-auto py-10 max-w-7xl flex flex-col gap-14"
				}, a.createElement(r, {
					type: "rise"
				}, a.createElement("div", {
					className: "max-w-xl flex flex-col gap-6"
				}, a.createElement("p", {
					className: "text-sm font-normal text-sky-500 tracking-widest uppercase"
				}, a.createElement(m, {
					propKey: "introTitle"
				}, e)), a.createElement("h1", {
					className: "TITLE-PRIMARY text-4xl font-semibold text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: "mainTitle"
				}, t)), a.createElement("p", {
					className: "DESC max-w-md text-base font-normal text-slate-700 dark:text-slate-300"
				}, a.createElement(m, {
					propKey: "description"
				}, o)))), a.createElement("div", {
					className: "grid grid-cols-1 gap-6 md:grid-cols-3"
				}, n.map((e, t) => a.createElement(r, {
					key: t,
					type: "rise"
				}, a.createElement("div", {
					className: "h-full bg-white rounded-md border border-black/10 dark:bg-slate-700 dark:border-white/10"
				}, a.createElement("div", {
					className: "h-full p-6 flex flex-col "
				}, a.createElement("h3", {
					className: "TITLE-SECONDARY text-sm font-normal tracking-widest text-slate-600 dark:text-slate-400 uppercase"
				}, `Step ${t + 1}`), a.createElement("p", {
					className: "TITLE-PRIMARY mt-8 text-xl font-medium text-slate-900 dark:text-slate-50"
				}, a.createElement(m, {
					propKey: `steps_${t}_title`
				}, e.title)), a.createElement("p", {
					className: "DESC mt-5 text-base font-normal text-slate-600 dark:text-slate-400"
				}, a.createElement(m, {
					propKey: `steps_${t}_description`
				}, e.description)))))))))
			}, {
				introTitle: "Proceso",
				mainTitle: "Cómo Implementamos Éxito",
				description: "Descubre los pasos que han seguido nuestros clientes para transformar su comunicación empresarial.",
				steps: [{
					title: "Análisis de Necesidades",
					description: "Comenzamos evaluando las necesidades específicas de comunicación de cada cliente.",
					imageKeyword: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/3486fbbd-d65c-4749-bf0c-83aef4c98abf.jpeg?oldPrompt=La primera imagen debe ilustrar el inicio del proceso de implementación de soluciones, mostrando a un grupo de profesionales empresariales discutiendo las necesidades y objetivos de comunicación de la empresa. Los elementos clave son personas reunidas alrededor de una mesa de trabajo con dispositivos móviles y laptops, enfatizando la planificación estratégica inicial."
				}, {
					title: "Configuración Personalizada",
					description: "Desarrollamos una solución de WhatsApp adaptada a los objetivos de negocio del cliente.",
					imageKeyword: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/15242dee-43c3-439e-8977-40eab46dabf6.jpeg?oldPrompt=La tercera imagen representa el éxito alcanzado por los clientes tras la implementación de las soluciones. Esta imagen debe mostrar a un equipo de personas celebrando en una sala de juntas, con gráficos de crecimiento o informes de éxito en una pantalla al fondo. La atmósfera general debe transmitir satisfacción y logro empresarial."
				}, {
					title: "Soporte Continuo",
					description: "Ofrecemos soporte y ajustes continuos para asegurar el éxito a largo plazo.",
					imageKeyword: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/e344188b-2233-4064-b3fb-8536d41c26ef.jpeg?oldPrompt=La segunda imagen se centra en la ejecución del plan, donde el enfoque es una escena de implementación activa. Debe incluir a un técnico o especialista configurando software en una computadora, mientras que un cliente observa el proceso en una oficina moderna. Elementos como pantallas, gráficos técnicos y dispositivos de comunicación deben destacar."
				}],
				key: "pwPZ1ayFOFJCj1ErHHPzJ"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "3pKdNP8kr_RtukaKsbWS8"
			}, a.createElement(function({
				title: e = "A Better Workflow for Your Design Studio",
				subtitle: t = "Deploy Faster",
				description1: o = "Innovative solutions tailored for the modern design studio. Streamline your creative process and bring your ideas to life faster and more efficiently.",
				description2: n = "Leverage cutting-edge technology to stay ahead in the fast-paced world of design. Our tools are designed to improve collaboration and productivity.",
				stats: s = [{
					label: "Founded",
					value: "2021"
				}, {
					label: "Projects Completed",
					value: "142"
				}, {
					label: "Happy Clients",
					value: "89"
				}, {
					label: "Awards Won",
					value: "15"
				}],
				imageUrl: l = "https://source.unsplash.com/random/800x600/?studio,design"
			}) {
				return a.createElement("section", {
					className: "bg-gray-900 py-16 sm:py-20"
				}, a.createElement("div", {
					className: "mx-auto max-w-7xl px-6 lg:px-8"
				}, a.createElement("div", {
					className: "mx-auto max-w-2xl lg:mx-0 lg:max-w-none"
				}, a.createElement("p", {
					className: "text-base font-semibold leading-7 text-sky-400"
				}, a.createElement(m, {
					propKey: "subtitle"
				}, t)), a.createElement("h1", {
					className: "TITLE-PRIMARY mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("div", {
					className: "mt-10 grid max-w-xl grid-cols-1 gap-8 text-base leading-7 text-slate-300 lg:max-w-none lg:grid-cols-2"
				}, a.createElement("div", null, a.createElement("p", {
					className: "DESC"
				}, a.createElement(m, {
					propKey: "description1"
				}, o)), a.createElement("p", {
					className: "DESC mt-8"
				}, a.createElement(m, {
					propKey: "description2"
				}, n))), a.createElement("div", null, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE w-full h-auto md:h-48 object-cover rounded-lg bg-slate-100 aspect-[4/3]",
					src: l,
					alt: ""
				}))), a.createElement("dl", {
					className: "mt-16 grid grid-cols-1 gap-x-8 gap-y-12 sm:mt-20 sm:grid-cols-2 sm:gap-y-16 lg:mt-28 lg:grid-cols-4"
				}, s.map((e, t) => a.createElement("div", {
					key: t,
					className: "flex flex-col-reverse gap-y-3 border-l border-white/20 pl-6"
				}, a.createElement("dt", {
					className: "TEXT-CONTENT text-base leading-7 text-gray-300"
				}, a.createElement(m, {
					propKey: `stats_${t}_label`
				}, e.label)), a.createElement("dd", {
					className: "text-3xl font-semibold tracking-tight text-white"
				}, a.createElement(m, {
					propKey: `stats_${t}_value`
				}, e.value))))))))
			}, {
				title: "Resultados Impresionantes",
				subtitle: "Éxito Medible",
				description1: "Nuestros clientes han experimentado un crecimiento significativo en la eficiencia de sus comunicaciones.",
				description2: "Con nuestras soluciones, han logrado una mayor satisfacción del cliente y una comunicación más fluida.",
				stats: [{
					label: "Clientes Satisfechos",
					value: "95%"
				}, {
					label: "Incremento de Ventas",
					value: "30%"
				}, {
					label: "Tiempo de Respuesta Reducido",
					value: "50%"
				}, {
					label: "Nuevas Implementaciones",
					value: "200+"
				}],
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/f30ac764-1cf4-4130-bcbc-043988bcf7b6.jpeg?oldPrompt=Representación de un gráfico de barras o líneas mostrando el crecimiento notable en el rendimiento o éxito de los clientes después de implementar las soluciones de 'Tu Solución Ideal'. Incluye elementos que destacan cifras clave y logros cuantificables, como porcentajes de aumento en eficiencia, satisfacción del cliente o ahorro de costos. La imagen debe comunicar la idea de progreso y éxito empresarial, alineándose con el tema de mejorar la comunicación a través de WhatsApp.",
				key: "3pKdNP8kr_RtukaKsbWS8"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "FUuQrYvPJSsseiVGupn4Z"
			}, a.createElement(function({
				testimonials: e = [{
					name: "Judith Black",
					position: "CEO of Tuple",
					quote: "Amet amet eget scelerisque tellus sit neque faucibus non eleifend. Integer eu praesent at a. Ornare\n              arcu gravida natoque erat et cursus tortor consequat at. Vulputate gravida sociis enim nullam\n              ultricies habitant malesuada lorem ac. Tincidunt urna dui pellentesque sagittis.",
					imageUrl: "https://source.unsplash.com/random/1200x1200/?portrait,design,1"
				}, {
					name: "Joseph Rodriguez",
					position: "CEO of Reform",
					quote: "Excepteur veniam labore ullamco eiusmod. Pariatur consequat proident duis dolore nulla veniam\n              reprehenderit nisi officia voluptate incididunt exercitation exercitation elit. Nostrud veniam sint\n              dolor nisi ullamco.",
					imageUrl: "https://source.unsplash.com/random/1200x1200/?portrait,design"
				}]
			}) {
				return a.createElement("section", {
					className: "bg-white dark:bg-slate-800 py-16 sm:py-20"
				}, a.createElement("div", {
					className: "mx-auto max-w-7xl px-6 lg:px-8"
				}, a.createElement("div", {
					className: "mx-auto grid max-w-2xl grid-cols-1 lg:mx-0 lg:max-w-none lg:grid-cols-2"
				}, e.map((e, t) => a.createElement("div", {
					key: `testimonial_${t}`,
					className: `flex flex-col ${t % 2 == 0 ? "pb-10 sm:pb-16 lg:pb-0 lg:pr-8 xl:pr-20" : "border-t border-black/10 dark:border-white/10 pt-10 sm:pt-16 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0 xl:pl-20"}`
				}, a.createElement("figure", {
					className: "flex flex-auto flex-col justify-between"
				}, a.createElement("blockquote", {
					className: "DESC text-lg leading-8 text-slate-900 dark:text-white/80"
				}, a.createElement(m, {
					propKey: `testimonials_${t}_quote`
				}, e.quote)), a.createElement("figcaption", {
					className: "mt-10 flex items-center gap-x-6"
				}, a.createElement(c, {
					propKey: `testimonials_${t}_imageUrl`,
					className: "IMAGE h-14 w-14 rounded-full bg-slate-100 object-cover aspect-square",
					src: e.imageUrl,
					alt: e.name
				}), a.createElement("div", {
					className: "text-base"
				}, a.createElement("div", {
					className: "TITLE-PRIMARY font-semibold text-slate-900 dark:text-white/80"
				}, a.createElement(m, {
					propKey: `testimonials_${t}_name`
				}, e.name)), a.createElement("div", {
					className: "TITLE-SECONDARY mt-1 text-slate-500 dark:text-white/80"
				}, a.createElement(m, {
					propKey: `testimonials_${t}_position`
				}, e.position))))))))))
			}, {
				testimonials: [{
					name: "Carlos López",
					position: "Director de Marketing en Innovate",
					quote: "Gracias a Tu Solución Ideal, hemos optimizado nuestra comunicación y hemos visto un aumento en la satisfacción de nuestros clientes.",
					imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/315ceec5-7d4c-4e92-b331-c6cf6e6d40f0.jpeg?oldPrompt=Un grupo de personas de negocios sonrientes, representando a nuestros clientes satisfechos, en un entorno de oficina moderno. Deben estar interactuando con dispositivos móviles, simbolizando el uso de soluciones de comunicación empresarial. El ambiente debe transmitir éxito y profesionalismo, alineado con la efectividad de nuestras soluciones."
				}, {
					name: "María González",
					position: "Gerente de Ventas en TechCorp",
					quote: "La integración de WhatsApp ha transformado nuestra forma de interactuar con los clientes, llevándonos a un nuevo nivel de eficiencia.",
					imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/7eab538b-09c7-455a-a49e-9781920f6805.jpeg?oldPrompt=Un empresario o empresaria mirando con satisfacción su dispositivo móvil en un espacio de trabajo moderno. La imagen debe simbolizar el logro del éxito y la satisfacción personal al usar nuestras soluciones de comunicación a través de WhatsApp. El escenario debe reflejar un entorno profesional y motivador, destacando la importancia de la comunicación eficiente para el logro de objetivos empresariales."
				}],
				key: "FUuQrYvPJSsseiVGupn4Z"
			}));
			t.push(e)
		} catch (e) {}
		try {
			const e = a.createElement(o, {
				key: "FiVRgX0KfKPx1gsWvaRq_"
			}, a.createElement(function({
				categories: e = [{
					name: "Web Design",
					images: [{
						src: "https://source.unsplash.com/random/800x700/?website,interface",
						title: "Landing Page Design",
						tag: "Web"
					}, {
						src: "https://source.unsplash.com/random/800x700/?template,theme",
						title: "WordPress Theme",
						tag: "Template"
					}, {
						src: "https://source.unsplash.com/random/800x700/?responsive,layout",
						title: "Responsive Layouts",
						tag: "Responsive"
					}, {
						src: "https://source.unsplash.com/random/800x700/?user,experience",
						title: "User Experience Project",
						tag: "UX"
					}]
				}, {
					name: "Graphic Design",
					images: [{
						src: "https://source.unsplash.com/random/800x700/?poster,graphic",
						title: "Marketing Poster",
						tag: "Graphic"
					}, {
						src: "https://source.unsplash.com/random/800x700/?branding,identity",
						title: "Corporate Branding",
						tag: "Branding"
					}, {
						src: "https://source.unsplash.com/random/800x700/?illustration,art",
						title: "Digital Illustrations",
						tag: "Art"
					}, {
						src: "https://source.unsplash.com/random/800x700/?print,design",
						title: "Print Design Suite",
						tag: "Print"
					}]
				}, {
					name: "UI/UX",
					images: [{
						src: "https://source.unsplash.com/random/800x700/?app,dashboard",
						title: "Dashboard UI Kit",
						tag: "UI/UX"
					}, {
						src: "https://source.unsplash.com/random/800x700/?mobile,interface",
						title: "Mobile App Interfaces",
						tag: "Mobile"
					}, {
						src: "https://source.unsplash.com/random/800x700/?wireframe,prototype",
						title: "Wireframing Sessions",
						tag: "Wireframe"
					}, {
						src: "https://source.unsplash.com/random/800x700/?ux,design",
						title: "UX Design Workshop",
						tag: "Workshop"
					}]
				}, {
					name: "Photography",
					images: [{
						src: "https://source.unsplash.com/random/800x700/?nature,travel",
						title: "Nature and Wildlife",
						tag: "Photo"
					}, {
						src: "https://source.unsplash.com/random/800x700/?city,life",
						title: "Urban City Life",
						tag: "Urban"
					}, {
						src: "https://source.unsplash.com/random/800x700/?portrait,photography",
						title: "Portrait Photography",
						tag: "Portrait"
					}, {
						src: "https://source.unsplash.com/random/800x700/?landscape,view",
						title: "Landscape Views",
						tag: "Landscape"
					}]
				}]
			}) {
				const [t, o] = a.useState(e[0]), [n, s] = a.useState(0);
				return a.createElement("div", {
					className: "w-full bg-white dark:bg-slate-800"
				}, a.createElement("div", {
					className: "w-full max-w-7xl mx-auto py-20 px-4"
				}, a.createElement("div", null, a.createElement("ul", {
					className: "filter-options flex flex-wrap justify-start gap-2 mb-6"
				}, e.map((e, n) => a.createElement("li", {
					key: e.name,
					className: "inline-block"
				}, a.createElement(d, {
					onClick: () => {
						o(e), s(n)
					},
					className: `inline-flex items-center justify-center font-medium border py-1.5 px-5 focus:outline-none hover:bg-slate-100 rounded-full text-sm sm:text-base 2xl:text-lg transition-colors duration-500 dark:hover:bg-slate-600  dark:border-slate-800 ${t.name === e.name ? "bg-sky-50 border-sky-200 text-sky-600 dark:bg-white" : "text-slate-900 hover:bg-sky-400 dark:text-slate-200 dark:hover:text-white"}`
				}, a.createElement(m, {
					propKey: `categories_${n}_name`
				}, e.name)))))), a.createElement("div", {
					className: "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"
				}, t.images.map((e, t) => a.createElement(r, {
					type: "rise"
				}, a.createElement("div", {
					className: "relative overflow-hidden rounded-lg shadow-lg group bg-slate-100 dark:bg-slate-900"
				}, a.createElement(c, {
					propKey: `categories_${n}_images_${t}_src`,
					className: "transition-transform object-cover w-full h-auto aspect-[4/3] duration-500 ease-in-out transform group-hover:scale-110",
					src: e.src
				}), a.createElement("div", {
					className: "absolute inset-0 pointer-events-none bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
				}), a.createElement("div", {
					className: "absolute flex pointer-events-none items-center gap-1.5 bottom-0 left-0 p-6 z-10 bg-gradient-to-t from-black w-full"
				}), a.createElement("div", {
					className: "absolute flex items-center gap-1.5 bottom-0 left-0 p-4 z-20"
				}, a.createElement("p", {
					className: "TITLE-PRIMARY text-white text-lg font-semibold"
				}, a.createElement(m, {
					propKey: `categories_${n}_images_${t}_title`
				}, e.title)), a.createElement("p", {
					className: "DESC text-xs inline-flex p-1.5 rounded font-medium bg-white/20 text-white"
				}, a.createElement(m, {
					propKey: `categories_${n}_images_${t}_tag`
				}, e.tag)))))))))
			}, {
				categories: [{
					name: "Comunicación Empresarial",
					images: [{
						src: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/249ae42b-9773-4c01-85ac-faad575a097e.jpeg?oldPrompt=Una imagen que muestra a un grupo de profesionales en una oficina moderna, colaborando y utilizando herramientas digitales para comunicarse eficazmente a través de WhatsApp. La imagen debe capturar una atmósfera de éxito y trabajo en equipo, destacando la integración de tecnología avanzada en el entorno empresarial.",
						title: "Estrategia de Comunicación",
						tag: "Estrategia"
					}, {
						src: "https://source.unsplash.com/random/800x700/?whatsapp,chat",
						title: "Interacción en Tiempo Real",
						tag: "Interacción"
					}]
				}, {
					name: "Casos de Éxito",
					images: [{
						src: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/3e419dc1-0504-470c-8af6-230f95d67d9b.jpeg?oldPrompt=Una imagen que representa un caso de éxito de un cliente utilizando nuestras soluciones. El escenario podría incluir una representación visual de un gráfico de crecimiento o un documento de logro, simbolizando el impacto positivo de la comunicación eficiente en los resultados empresariales.",
						title: "Transformación Digital",
						tag: "Transformación"
					}, {
						src: "https://source.unsplash.com/random/800x700/?growth,chart",
						title: "Crecimiento Empresarial",
						tag: "Crecimiento"
					}]
				}],
				key: "FiVRgX0KfKPx1gsWvaRq_"
			}));
			t.push(e)
		} catch (e) {}
		try {
			function v({
				title: e = "Get in Touch with Our Design Studio",
				description: t = "Our team is eager to discuss your design needs and help bring your creative visions to life with our professional expertise.",
				imageUrl: o = "https://source.unsplash.com/1000x800/?office,communication",
				officeHours: n = [{
					region: "USA Office Hours",
					hours: "Monday-Friday\n8:00 am to 5:00 pm"
				}, {
					region: "Canada Office",
					hours: "Monday-Friday\n9:00 am to 6:00 pm"
				}],
				address: s = {
					title: "Our Address",
					label: "8502 Preston Rd. Ingle, Maine 98380, USA"
				},
				contact: l = {
					title: "Get in touch",
					label: ["+1-246-888-0653", "+1-222-632-0194"]
				}
			}) {
				return a.createElement("section", {
					className: "py-10 bg-white dark:bg-slate-800 sm:py-16 lg:py-20 xl:py-24"
				}, a.createElement("div", {
					className: "max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
				}, a.createElement("div", {
					className: "grid grid-cols-1 lg:grid-cols-2 gap-y-8 gap-x-36"
				}, a.createElement("div", null, a.createElement("h2", {
					className: "TITLE-PRIMARY text-4xl font-semibold text-slate-900 dark:text-slate-50 md:text-6xl"
				}, a.createElement(m, {
					propKey: "title"
				}, e)), a.createElement("p", {
					className: "DESC mt-6 text-base font-normal text-slate-700 dark:text-slate-300 "
				}, a.createElement(m, {
					propKey: "description"
				}, t))), a.createElement(r, {
					type: "rise"
				}, a.createElement(c, {
					propKey: "imageUrl",
					className: "IMAGE rounded-lg bg-slate-100 w-[37.5rem] h-[25rem] object-cover aspect-[3/2]",
					src: o,
					alt: o
				}))), a.createElement("div", {
					className: "grid grid-cols-1 mt-12 sm:grid-cols-2 lg:grid-cols-4 xl:gap-20 gap-6 sm:gap-10 sm:mt-16 lg:mt-20"
				}, n.map((e, t) => a.createElement("div", {
					key: t
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: `officeHours_${t}_region`
				}, e.region)), a.createElement("p", {
					className: "TEXT-CONTENT mt-5 text-base font-medium text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: `officeHours_${t}_hours`
				}, e.hours)))), a.createElement("div", {
					className: "flex flex-col gap-5"
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: "address_title"
				}, s.title)), a.createElement("p", {
					className: "TEXT-CONTENT text-base font-medium text-slate-900 dark:text-slate-200"
				}, a.createElement(m, {
					propKey: "address_label"
				}, s.label))), a.createElement("div", {
					className: "flex flex-col gap-5"
				}, a.createElement("p", {
					className: "TEXT-CONTENT text-xs font-semibold tracking-widest text-sky-600 uppercase"
				}, a.createElement(m, {
					propKey: "contact_title"
				}, l.title)), a.createElement("p", {
					className: "TEXT-CONTENT  text-base font-medium text-slate-900 dark:text-slate-200"
				}, l.label.map((e, t) => a.createElement(a.Fragment, null, a.createElement(m, {
					propKey: `contact_label_${t}`,
					key: t
				}, e))))))))
			}
			const e = a.createElement(o, {
				key: "J8yGBe5J1ymi3GxgEHrSE"
			}, a.createElement(v, {
				title: "Contáctanos para Impulsar tu Negocio",
				description: "Nuestro equipo está listo para ayudarte a mejorar tu comunicación empresarial con soluciones personalizadas.",
				imageUrl: "https://cdn.a1.art/assets/images/app_1811317900177637378/1811317900181831681/d1299d7a-a34d-4e78-a8eb-1b5bbe6cdfbe.jpeg?oldPrompt=La imagen debe representar un ambiente de colaboración y comunicación efectiva. Incluye a un grupo diverso de profesionales en una oficina moderna, interactuando con dispositivos digitales como teléfonos inteligentes y computadoras portátiles, simbolizando la conexión a través de WhatsApp. Deben estar involucrados en una conversación productiva, lo que refleja cómo la plataforma ayuda a las empresas a lograr el éxito. Un fondo de oficina contemporánea resalta la modernidad y profesionalismo de las soluciones ofrecidas por 'Tu Solución Ideal'.",
				officeHours: [{
					region: "Horario de Oficina en España",
					hours: "Lunes a Viernes\n9:00 am a 6:00 pm"
				}, {
					region: "Horario de Oficina en México",
					hours: "Lunes a Viernes\n8:00 am a 5:00 pm"
				}],
				address: {
					title: "Nuestra Dirección",
					label: "Calle de la Innovación, 123, Madrid, España"
				},
				contact: {
					title: "Ponte en Contacto",
					label: ["+34-123-456-789", "+52-987-654-321"]
				},
				key: "J8yGBe5J1ymi3GxgEHrSE"
			}));
			t.push(e)
		} catch (e) {}
		e.push({
			path: "casos-de-exito",
			element: a.createElement(w, null, t)
		})
	}
	try {
		y = a.createElement(o, {
			key: "9yl3jlAHJWW5iVgvMb9O1"
		}, a.createElement(function({
			companySections: e = [{}, {}, {}, {}],
			socialLinks: t = [{
				icon: "fa-brands fa-whatsapp",
				url: "#"
			}, {
				icon: "fa-brands fa-linkedin",
				url: "#"
			}, {
				icon: "fa-brands fa-youtube",
				url: "#"
			}, {
				icon: "fa-brands fa-twitter",
				url: "#"
			}],
			footerText: o = "© Tu Solución Ideal 2023, Todos los Derechos Reservados"
		}) {
			return a.createElement("section", {
				className: "w-full bg-slate-50 dark:bg-slate-800 py-20 px-6 md:px-8"
			}, a.createElement(r, {
				type: "rise"
			}, a.createElement("div", {
				className: "mx-auto max-w-7xl w-full flex flex-col gap-12"
			}, a.createElement("div", {
				className: "w-full flex flex-wrap gap-10 items-center justify-center"
			}, e.map((e, t) => a.createElement("div", {
				key: t
			}, a.createElement(d, {
				className: "TITLE-PRIMARY text-base font-semibold text-slate-900 dark:text-slate-50"
			}, a.createElement(m, {
				propKey: `companySections_${t}_titleAttr`
			}, e.titleAttr))))), a.createElement("hr", {
				className: "w-full border-black/10 dark:border-white/10"
			}), a.createElement("div", {
				className: "flex flex-col items-center gap-6 justify-between md:flex-row"
			}, a.createElement("ul", {
				className: "flex items-center space-x-6"
			}, t.map((e, t) => a.createElement("li", {
				key: e.icon,
				href: e.url
			}, a.createElement(d, {
				className: "TEXT-LINK text-base text-slate-900 hover:text-sky-400 focus:text-sky-500 dark:text-slate-50 dark:hover:text-sky-400 dark:focus:text-sky-500"
			}, a.createElement(p, {
				propKey: `socialLinks_${t}_icon`,
				icon: e.icon,
				iconLibrary: "FontAwesome",
				className: "text-lg"
			}))))), a.createElement("p", {
				className: "DESC text-sm text-center text-slate-600 dark:text-slate-400"
			}, a.createElement(m, {
				propKey: "footerText"
			}, o))))))
		}, {
			companySections: [{}, {}, {}, {}],
			socialLinks: [{
				icon: "fa-brands fa-whatsapp",
				url: "#"
			}, {
				icon: "fa-brands fa-linkedin",
				url: "#"
			}, {
				icon: "fa-brands fa-youtube",
				url: "#"
			}, {
				icon: "fa-brands fa-twitter",
				url: "#"
			}],
			footerText: "© Tu Solución Ideal 2023, Todos los Derechos Reservados",
			key: "9yl3jlAHJWW5iVgvMb9O1"
		}))
	} catch (e) {} {
		const t = e.find(e => {
			if ("inicio" === e.path) return e
		});
		e.unshift({
			path: "/",
			element: t.element
		})
	}
	const k = l(e);
	t(document.getElementById("root")).render(a.createElement(i, {
		router: k
	}))
}! function() {
	const e = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
	["#EBFFE8", "#C7EFC3", "#A5DFA0", "#84CF81", "#65BF65", "#4CAF50", "#369B3D", "#23882D", "#147421", "#086017", "#004D0F"].forEach((t, a) => {
		const o = `--ai-create-color-theme-${e[a]}`;
		document.documentElement.style.setProperty(o, t)
	})
}(), [{
	name: "Montserrat",
	key: "--custom-heading-font",
	fontFamily: 'Montserrat,ui-sans-serif, system-ui, -apple-system, blinkmacsystemfont, "Segoe UI", roboto, "Helvetica Neue", arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
}, {
	name: "Roboto",
	key: "--custom-body-font",
	fontFamily: 'Roboto,ui-sans-serif, system-ui, -apple-system, blinkmacsystemfont, "Segoe UI", roboto, "Helvetica Neue", arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
}].forEach(({
	name: e,
	key: t,
	fontFamily: a
}) => {
	if (e && "undefined" !== e) {
		const o = new FontFace(e, `url("/static/font/${e}.ttf")`);
		document.fonts.add(o), o.load(), document.documentElement.style.setProperty(t, a)
	}
}), e();
