if ("JotformFeedbackDefaultSettings" in window) {
  var JotformFeedbackDefaultSettings = { buttonText: "Feedback", verticalTextPadding: 7, horizontalTextPadding: 15, buttonSpace: 35, titleHeight: 35, height: 500, width: 700, compact: true, instant: false, fontFamily: "verdana", fontColor: "#FFFFFF", fontStyle: "bold", background: "#F59202 center no-repeat", hoverBackground: "#5C5C5C", hoverFontColor: "#FFFFFF", buttonSide: "right", buttonAlign: "center", margin: 50, base: "http://www.jotform.com/", formId: null, windowTitleBgColor: false, windowTitleFontColor: false, fontSize: "15px", buttonOffset: 0, inlineStyle: "", iframeParameters: {}, setBorderRadius: true, type: 0, openOnLoad: false, reverseButtonText: false, reCalculate: true, onFormLoaded: function () { }, };
}

if ("JotformFeedbackManager" in window) {
  var JotformFeedbackManager = {
    buttons: {},
    addNewButton: function (button, reCalculate) {
      JotformFeedbackManager.reCalculate = reCalculate;
      var key = button.options.buttonSide + button.options.buttonAlign;
      if (!Array.isArray(JotformFeedbackManager.buttons[key])) {
        JotformFeedbackManager.buttons[key] = [];
      }
      JotformFeedbackManager.buttons[key][JotformFeedbackManager.buttons[key].length] = button;
      JotformFeedbackManager.setButtons(key);
    },
    setButtons: function (key) {
      var total = 0, i, tempButton;
      for (i = 0; i < JotformFeedbackManager.buttons[key].length; i++) {
        tempButton = JotformFeedbackManager.buttons[key][i];
        total += tempButton.getButtonWidth();
        if (i !== JotformFeedbackManager.buttons[key].length - 1) {
          total += tempButton.options.buttonOffset;
        }
      }
      var current = 0;
      for (i = 0; i < JotformFeedbackManager.buttons[key].length; i++) {
        tempButton = JotformFeedbackManager.buttons[key][i];
        if (JotformFeedbackManager.reCalculate) {
          tempButton.calculateButtonPosition(total, current);
        } else {
          tempButton.calculateButtonPosition(0, 0);
        }
        tempButton.showButton();
        current += tempButton.getButtonWidth();
        if (i !== JotformFeedbackManager.buttons[key].length - 1) {
          current += tempButton.options.buttonOffset;
        }
      }
    }
  };
}
class JotformFeedback {
  constructor(options) {
    this.calculateButtonPosition = this.calculateButtonPosition.bind(this);
    this.createButton = this.createButton.bind(this);
    this.createWindow = this.createWindow.bind(this);
    this.css = this.css.bind(this);
    this.embedButton = this.embedButton.bind(this);
    this.generateBackgroundForWindow = this.generateBackgroundForWindow.bind(this);
    this.generateBoxRadius = this.generateBoxRadius.bind(this);
    this.getButtonHeight = this.getButtonHeight.bind(this);
    this.getButtonWidth = this.getButtonWidth.bind(this);
    this.mouseOut = this.mouseOut.bind(this);
    this.mouseOver = this.mouseOver.bind(this);
    this.setButtonListeners = this.setButtonListeners.bind(this);
    this.setButtons = this.setButtons.bind(this);
    this.setHoverProperties = this.setHoverProperties.bind(this);
    this.setLinkListener = this.setLinkListener.bind(this);
    this.setOriginPointAfterRotation = this.setOriginPointAfterRotation.bind(this);
    this.setTransform = this.setTransform.bind(this);
    this.showButton = this.showButton.bind(this);
    this.showForm = this.showForm.bind(this);
    this.showWindow = this.showWindow.bind(this);
    options.isLink = !("buttonText" in options); this.options = { ...JotformFeedbackDefaultSettings, ...options };
    this.formWindow = null;
    if (this.options.isCardForm) {
      this.options.height = '100%'; this.options.width = '100%'; this.options.hideTitle = true;
    }
    this.options.windowTitle = this.options.windowTitle === undefined ? this.options.buttonText : this.options.windowTitle;
    this.options.windowTitleBgColor = this.options.windowTitleBgColor === false ? this.options.background : this.options.windowTitleBgColor;
    this.options.windowTitleFontColor = this.options.windowTitleFontColor === false ? this.options.fontColor : this.options.windowTitleFontColor;
    this.documentLoaded = document.readyState == 'complete' || (this.jsForm && document.readyState === undefined);
    if (this.options.isLink) {
      if (!this.options.openOnLoad) { if (this.documentLoaded) { this.setLinkListener(); } else { document.addEventListener('readystatechange', () => { document.readyState === 'complete' && this.setLinkListener(); }); } }
    } else { if (this.documentLoaded) { this.setButtons(); } else { document.addEventListener('readystatechange', () => { document.readyState === 'complete' && this.setButtons(); }); } }

    if (this.options.openOnLoad === true) {
      if (this.documentLoaded) {
        this.showWindow();
      } else {
        document.addEventListener('readystatechange', () => {
          document.readyState === 'complete' && this.showWindow();
        });
      }
    }
  }
  setButtons () {
    this.createButton();
    JotformFeedbackManager.addNewButton(this, this.options.reCalculate);
  }
  setLinkListener () {
    // NOTE: setLinkListener 和 react 不在一个生命周期内，react 重新渲染时，setLinkListener 不会重新绑定，选择手动绑定
    // var linkIdentifier = 'lightbox-' + this.options.formId;
    // document.querySelectorAll("." + linkIdentifier).forEach((identifier) => {
    //   try {
    //     console.debug('identifier', identifier)
    //     identifier.addEventListener('click', () => {
    //       console.debug('click')
    //       this.showWindow();
    //     });
    //   } catch (error) {
    //     console.debug('error', error)
    //   }

    // });

    // var idIdentified = document.querySelector('#' + linkIdentifier);

    // if (idIdentified) {
    //   idIdentified.addEventListener('click', this.showWindow);
    // }
  }
  setHoverProperties (button) {
    button.addEventListener('mouseover', this.mouseOver);
    button.addEventListener('mouseout', this.mouseOut);
  }
  mouseOver () {
    this.css(this.button, { "background": this.options.hoverBackground, "color": this.options.hoverFontColor, textShadow: "0px 0px 10px rgba(255,255,255,0.6)" });
  }
  mouseOut () {
    this.css(this.button, { "background": this.options.background, "color": this.options.fontColor, textShadow: "none" });
  }
  getButtonWidth () { return this.button.offsetWidth; }
  getButtonHeight () { return this.button.offsetHeight; }
  createButton () {
    var style = this.setTransform() + "padding:" + this.options.verticalTextPadding + "px " + this.options.horizontalTextPadding + "px;" + "position:fixed;" +
      this.options.buttonSide + ":0;" + "visibility:hidden;" + "font-family:" + this.options.fontFamily + ";" + "font-size:" + this.options.fontSize + ";" + "color:" + this.options.fontColor + ";" + "background:" + this.options.background + ";" + "font-style: " + this.options.fontStyle + ";" + "z-index: 999;" + "cursor:pointer;" + "text-align: center;" + "text-decoration: none;" +
      this.options.inlineStyle + ";"; if (this.options.setBorderRadius) { style += this.generateBoxRadius(); }
    this.button = document.createElement('a');
    this.button.setAttribute('class', 'jotform-feedback-link');
    this.button.setAttribute('id', 'jotform-feedback-' + this.options.formId);
    this.button.setAttribute('style', style);
    this.button.innerHTML = this.options.buttonText;
    this.setHoverProperties(this.button);
    this.embedButton(this.button);
    this.setButtonListeners(this.button);
  }
  generateBoxRadius () {
    var radiusValues = "";
    var shadowValues = "0 0 5px";
    switch (this.options.buttonSide) {
      case "bottom":
        radiusValues = "5px 5px 0 0";
        break;
      case "top":
        radiusValues = "0 0 5px 5px";
        break;
      case "right":
        if (this.options.reverseButtonText) {
          radiusValues = "0 0 5px 5px";
        } else {
          radiusValues = "5px 5px 0 0";
        }
        shadowValues = "";
        break;
      case "left":
        if (this.options.reverseButtonText) {
          radiusValues = "5px 5px 0 0";
        } else {
          radiusValues = "0 0 5px 5px";
        }
        shadowValues = "";
        break;
    }
    return "-moz-border-radius: " + radiusValues + ";" + "-webkit-border-radius: " + radiusValues + ";" + "border-radius: " + radiusValues + ";" + "-moz-box-shadow: " + shadowValues + " rgba(0,0,0,0.3);" + "-webkit-box-shadow: " + shadowValues + " rgba(0,0,0,0.3);" + "box-shadow: " + shadowValues + " rgba(0,0,0,0.3);";
  }
  setTransform () {
    if (this.options.buttonSide === "left" || this.options.buttonSide === "right") {
      var degree = -90;
      if (this.options.reverseButtonText) {
        degree = 90;
      }
      return "-moz-transform:rotate(" + degree.toString() + "deg);" + "-o-transform:rotate(" + degree.toString() + "deg);" + "-webkit-transform:rotate(" + degree.toString() + "deg);" + "transform:rotate(" + degree.toString() + "deg);";
    }
    return "";
  }
  embedButton (button) {
    document.querySelector('body').append(button);
  }
  setButtonListeners (button) {
    button.addEventListener('click', this.showForm);
  }
  showButton () {
    this.css(this.button, { visibility: "" })
  }
  setOriginPointAfterRotation () {
    switch (this.options.buttonSide) {
      case "left":
        this.css(this.button, {
          marginLeft: -1 * (this.getButtonWidth() / 2 - this.getButtonHeight() / 2) + "px"
        })
        break;
      case "right":
        this.css(this.button, { marginRight: -1 * (this.getButtonWidth() / 2 - this.getButtonHeight() / 2) + "px" });
        break;
      default:
        break;
    }
  }
  calculateButtonPosition (total, current) {
    this.setOriginPointAfterRotation(); switch (this.options.buttonAlign) {
      case "top": var css = { top: this.options.buttonSpace + current + "px", marginTop: (this.getButtonWidth() / 2 - this.getButtonHeight() / 2) + "px" }; this.css(this.button, css); break; case "bottom": var css = { bottom: this.options.buttonSpace + current + "px", marginBottom: (this.getButtonWidth() / 2 - this.getButtonHeight() / 2) + "px" }; this.css(this.button, css); break; case "left": this.css(this.button, { left: (this.options.buttonSpace + current) + "px" }); break; case "right": this.css(this.button, { right: (this.options.buttonSpace + current) + "px" })
        break; case "center": if (this.options.buttonSide === "left" || this.options.buttonSide === "right") {
          var marginValue = -1 * (total / 2 - this.getButtonWidth() / 2) + -1 * this.getButtonHeight() / 2 + current
          this.css(this.button, { top: this.options.margin + "%", marginTop: marginValue + "px" })
        } else { this.css(this.button, { left: this.options.margin + "%", marginLeft: (-1 * total / 2 + current) + "px" }); }
        break;
    }
  }
  showWindow () {
    var $this = this;
    var settings = {
      titleBackground: this.generateBackgroundForWindow(),
      buttonsBackground: '#fff url(' + this.options.base + 'images/footer-bg.png)', background: '#f5f5f5', borderWidth: 0, borderOpacity: 0.5, borderRadius: '10px', contentPadding: 0, closeButton: '<div style="width:22px;margin-right: 6px;margin-top: 6px; height:25px; background:url(' +
        this.options.base + '/images/Close-icon-new.png) no-repeat !important;">' + '</div>', dimColor: '#000', dimOpacity: 0.8, borderColor: '#000', titleTextColor: this.options.windowTitleFontColor
    };
    var types = [{}, { borderWidth: 10, borderOpacity: 1, borderColor: '#fff', hideTitle: true, closeButton: '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10.5" fill="#fff"/><path fill-rule="evenodd" clip-rule="evenodd" d="M7.34993 5.93572C6.9594 5.54519 6.32624 5.54519 5.93572 5.93572C5.54519 6.32624 5.54519 6.9594 5.93572 7.34993L9.08581 10.5L5.93583 13.65C5.5453 14.0405 5.5453 14.6737 5.93583 15.0642C6.32635 15.4547 6.95952 15.4547 7.35004 15.0642L10.5 11.9142L13.65 15.0642C14.0405 15.4547 14.6737 15.4547 15.0642 15.0642C15.4547 14.6737 15.4547 14.0405 15.0642 13.65L11.9142 10.5L15.0643 7.34993C15.4549 6.9594 15.4549 6.32624 15.0643 5.93572C14.6738 5.54519 14.0406 5.54519 13.6501 5.93572L10.5 9.08581L7.34993 5.93572Z" fill="black"/></svg>' }, { borderWidth: 10, borderOpacity: 0.8, hideTitle: true, closeButton: '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="11" fill="#000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M7.70711 6.29289C7.31658 5.90237 6.68342 5.90237 6.29289 6.29289C5.90237 6.68342 5.90237 7.31658 6.29289 7.70711L9.58742 11.0016L6.29293 14.2961C5.90241 14.6867 5.90241 15.3198 6.29293 15.7103C6.68346 16.1009 7.31662 16.1009 7.70715 15.7103L11.0016 12.4159L14.2961 15.7103C14.6867 16.1009 15.3198 16.1009 15.7103 15.7103C16.1009 15.3198 16.1009 14.6867 15.7103 14.2961L12.4159 11.0016L15.7104 7.70711C16.1009 7.31658 16.1009 6.68342 15.7104 6.29289C15.3199 5.90237 14.6867 5.90237 14.2962 6.29289L11.0016 9.58742L7.70711 6.29289Z" fill="white"/></svg>', borderColor: '#000' }];
    settings = { ...settings, ...types[this.options.type] };
    this.windowDefaults = { ...this.windowDefaults, ...settings };
    window.wishboxCompactLoad = this.options.compact;
    var w = this.options.width;
    var h = this.options.height;
    if (this.options.compact && this.options.instant) {
      w = window.innerWidth - 100; h = window.innerHeight - 100;
    }
    this.formWindow = this.createWindow({
      title: this.options.windowTitle, width: w + "px", height: h + "px", content: '<div id="js-form-content"></div>', onInsert: () => {
        this.jsFormScriptElement = document.createElement('script');
        this.jsFormScriptElement.setAttribute('type', 'text/javascript');
        this.jsFormScriptElement.setAttribute('src', $this.options.base + "jsform/" + $this.options.formId + "?appendTo=js-form-content&" + new URLSearchParams($this.options.iframeParameters).toString());
        this.jsFormScriptElement.addEventListener('load', () => {
          document.getElementById($this.options.formId).contentWindow.document.querySelector('html').classList.add('isLightbox');
          this.jsFormScriptElement.remove();
        })
        document.querySelector('body').append(this.jsFormScriptElement);
      }, backgroundColor: this.options.backgroundColor,
    });
    window.JotFormFrameLoaded = () => {
      if (!$this.options.isCardForm) {
        $this.options.onFormLoaded.call($this);
        $this.css(document.querySelector('#js-form-content iframe'), {
          minHeight: $this.options.height + 'px', backgroundColor: "white"
        });
      }
      var jtDimmer = document.querySelector('.jt-dimmer');
      if (jtDimmer) {
        jtDimmer.addEventListener('click', () => {
          jtDimmer.remove(); document.querySelector('.jt-feedback').remove();
          this.responsiveLightboxStyle.remove();
        });
      }
    };
  }
  createWindow (options) {
    options = { ...options, ...this.windowDefaults };
    this.responsiveLightboxStyle = document.createElement('style');
    this.responsiveLightboxStyle.setAttribute('type', 'text/css');
    this.responsiveLightboxStyle.innerHTML = '@media screen and (max-width: 768px) { .u-responsive-lightbox { width: 90%!important; } .u-responsive-lightbox .jt-title, .u-responsive-lightbox .jt-content, .u-responsive-lightbox .js-form-content { width: 100%!important; }}';
    document.querySelector('head').append(this.responsiveLightboxStyle);
    var dimmer = document.createElement('div');
    dimmer.setAttribute('class', 'jt-dimmer');
    this.css(dimmer, { position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: options.dimColor, opacity: options.dimOpacity, zIndex: 10000000 });
    document.querySelector('body').append(dimmer);
    var props = {
      position: 'fixed',
      top: this.options.isCardForm ? 0 : '50%',
      left: this.options.isCardForm ? 0 : '50%',
      marginLeft: this.options.isCardForm ? 0 : "-" + (parseFloat(options.width) / 2) + 'px',
      marginTop: this.options.isCardForm ? 0 : "-" + (parseFloat(options.height) / 2) + 'px',
      zIndex: 10000001
    };
    if (this.options.isCardForm) {
      props.height = '100%'; props.width = '100%';
    }
    var cont = document.createElement('div');
    this.css(cont, props);
    cont.setAttribute('class', 'jt-feedback u-responsive-lightbox');
    document.querySelector('body').append(cont);
    var reCenter = () => {
      this.css(cont, {
        marginTop: "-" + Math.floor(parseInt(cont.offsetHeight, 10) / 2) + "px", marginLeft: "-" + Math.floor(parseInt(cont.offsetWidth, 10) / 2) + "px"
      });
      this.css(win, { overflow: 'auto', maxHeight: (window.innerHeight - (this.options.titleHeight * 2)) + 'px' })
    };
    if (!this.options.isCardForm) {
      var i = setInterval(() => { reCenter(); }, 200);
    }
    var border = document.createElement('div');
    var borderWidth = parseInt(options.borderWidth, 10);
    this.css(border, {
      position: 'absolute',
      top: `-${borderWidth}px`,
      left: `-${borderWidth}px`,
      right: `-${borderWidth}px`,
      bottom: `-${borderWidth}px`,
      opacity: options.borderOpacity,
      backgroundColor: options.borderColor,
      zIndex: -1,
      borderRadius: options.borderRadius,
      MoxBorderRadius: options.borderRadius,
      WebkitBorderRadius: options.borderRadius
    });
    cont.append(border);
    var title = document.createElement('div');
    title.setAttribute('class', 'jt-title');
    title.textContent = options.title;
    this.css(title, { backgroundColor: options.backgroundColor || options.titleBackground, width: (parseInt(options.width, 10) + 20) + 'px', position: 'relative', fontWeight: 'bold', color: options.titleTextColor, textIndent: '10px', borderBottom: '1px solid #8E8787', fontSize: '14px', height: options.titleHeight + 'px', lineHeight: '35px', fontFamily: 'Verdana', MozBorderRadius: '3px 3px 0px 0px', WebkitBorderRadius: '3px 3px 0px 0px', borderRadius: '3px 3px 0px 0px', cursor: 'move' }); cont.append(title);
    var close = document.createElement('div');
    close.innerHTML = options.closeButton;
    this.css(close, { height: '35px', top: 0, right: 0, lineHeight: '35px', cursor: 'pointer', position: 'absolute' });
    close.addEventListener('click', () => {
      if (i) {
        clearInterval(i);
      }
      dimmer.remove();
      cont.remove();
      this.responsiveLightboxStyle.remove();
    })
    if (options.hideTitle || this.options.isCardForm) {
      this.css(title, { display: 'none' });
      cont.append(close);
      this.css(close, {
        zIndex: 9999, position: "absolute", right: '-15px', top: '-18px'
      })
    } else {
      title.append(close);
    }
    var win = document.createElement('div');
    win.setAttribute('class', 'jt-content');
    this.css(win, {
      WebkitOverflowScrolling: 'touch', overflow: 'auto', background: options.background, position: 'relative', width: this.options.isCardForm ? '100%' : (parseInt(options.width, 10) + 20) + 'px', height: this.options.isCardForm ? '100%' : (parseInt(options.height, 10)) + 'px', padding: options.contentPadding, MozBorderRadius: '0px 0px 3px 3px', WebkitBorderRadius: '0px 0px 3px 3px', borderRadius: '0px 0px 3px 3px'
    });
    win.innerHTML = options.content; cont.append(win);
    var result = options.onInsert && options.onInsert(win);
    if (this.options.isCardForm) {
      var jtFeedback = document.querySelector('#js-form-content, #js-form-content iframe, .jt-feedback'); this.css(jtFeedback, { minWidth: "100%", minHeight: "100%", height: "100%", width: "100%" })
    }
    return cont;
  }
  generateBackgroundForWindow () {
    if (this.options.windowTitleBgColor === false) { return 'url(' + this.options.base + 'images/title-bg.png)'; } else { return this.options.windowTitleBgColor; }
  }
  showForm () {
    return this.showWindow();
  }
  css (node, styles) {
    Object.keys(styles).forEach((key) => { node.style[key] = styles[key]; });
  }
};
window.JotformFeedback = JotformFeedback;
var scripts = document.getElementsByTagName("script");
eval(scripts[scripts.length - 1].innerHTML);