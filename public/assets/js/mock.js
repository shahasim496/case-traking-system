(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["Mock"] = factory();
	else
		root["Mock"] = factory();
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	/* global require, module, window */
	var Handler = __webpack_require__(1)
	var Util = __webpack_require__(3)
	var Random = __webpack_require__(5)
	var RE = __webpack_require__(20)
	var toJSONSchema = __webpack_require__(23)
	var valid = __webpack_require__(25)

	var XHR
	if (typeof window !== 'undefined') XHR = __webpack_require__(27)

	/*!
	    Mock - æ¨¡æ‹Ÿè¯·æ±‚ & æ¨¡æ‹Ÿæ•°æ®
	    https://github.com/nuysoft/Mock
	    å¢¨æ™º mozhi.gyy@taobao.com nuysoft@gmail.com
	*/
	var Mock = {
	    Handler: Handler,
	    Random: Random,
	    Util: Util,
	    XHR: XHR,
	    RE: RE,
	    toJSONSchema: toJSONSchema,
	    valid: valid,
	    heredoc: Util.heredoc,
	    setup: function(settings) {
	        return XHR.setup(settings)
	    },
	    _mocked: {}
	}

	Mock.version = '1.0.1-beta2'

	// é¿å…å¾ªçŽ¯ä¾èµ–
	if (XHR) XHR.Mock = Mock

	/*
	    * Mock.mock( template )
	    * Mock.mock( function() )
	    * Mock.mock( rurl, template )
	    * Mock.mock( rurl, function(options) )
	    * Mock.mock( rurl, rtype, template )
	    * Mock.mock( rurl, rtype, function(options) )

	    æ ¹æ®æ•°æ®æ¨¡æ¿ç”Ÿæˆæ¨¡æ‹Ÿæ•°æ®ã€‚
	*/
	Mock.mock = function(rurl, rtype, template) {
	    // Mock.mock(template)
	    if (arguments.length === 1) {
	        return Handler.gen(rurl)
	    }
	    // Mock.mock(rurl, template)
	    if (arguments.length === 2) {
	        template = rtype
	        rtype = undefined
	    }
	    // æ‹¦æˆª XHR
	    if (XHR) window.XMLHttpRequest = XHR
	    Mock._mocked[rurl + (rtype || '')] = {
	        rurl: rurl,
	        rtype: rtype,
	        template: template
	    }
	    return Mock
	}

	module.exports = Mock

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	/* 
	    ## Handler

	    å¤„ç†æ•°æ®æ¨¡æ¿ã€‚
	    
	    * Handler.gen( template, name?, context? )

	        å…¥å£æ–¹æ³•ã€‚

	    * Data Template Definition, DTD
	        
	        å¤„ç†æ•°æ®æ¨¡æ¿å®šä¹‰ã€‚

	        * Handler.array( options )
	        * Handler.object( options )
	        * Handler.number( options )
	        * Handler.boolean( options )
	        * Handler.string( options )
	        * Handler.function( options )
	        * Handler.regexp( options )
	        
	        å¤„ç†è·¯å¾„ï¼ˆç›¸å¯¹å’Œç»å¯¹ï¼‰ã€‚

	        * Handler.getValueByKeyPath( key, options )

	    * Data Placeholder Definition, DPD

	        å¤„ç†æ•°æ®å ä½ç¬¦å®šä¹‰

	        * Handler.placeholder( placeholder, context, templateContext, options )

	*/

	var Constant = __webpack_require__(2)
	var Util = __webpack_require__(3)
	var Parser = __webpack_require__(4)
	var Random = __webpack_require__(5)
	var RE = __webpack_require__(20)

	var Handler = {
	    extend: Util.extend
	}

	/*
	    template        å±žæ€§å€¼ï¼ˆå³æ•°æ®æ¨¡æ¿ï¼‰
	    name            å±žæ€§å
	    context         æ•°æ®ä¸Šä¸‹æ–‡ï¼Œç”ŸæˆåŽçš„æ•°æ®
	    templateContext æ¨¡æ¿ä¸Šä¸‹æ–‡ï¼Œ

	    Handle.gen(template, name, options)
	    context
	        currentContext, templateCurrentContext, 
	        path, templatePath
	        root, templateRoot
	*/
	Handler.gen = function(template, name, context) {
	    /* jshint -W041 */
	    name = name == undefined ? '' : (name + '')

	    context = context || {}
	    context = {
	            // å½“å‰è®¿é—®è·¯å¾„ï¼Œåªæœ‰å±žæ€§åï¼Œä¸åŒ…æ‹¬ç”Ÿæˆè§„åˆ™
	            path: context.path || [Constant.GUID],
	            templatePath: context.templatePath || [Constant.GUID++],
	            // æœ€ç»ˆå±žæ€§å€¼çš„ä¸Šä¸‹æ–‡
	            currentContext: context.currentContext,
	            // å±žæ€§å€¼æ¨¡æ¿çš„ä¸Šä¸‹æ–‡
	            templateCurrentContext: context.templateCurrentContext || template,
	            // æœ€ç»ˆå€¼çš„æ ¹
	            root: context.root || context.currentContext,
	            // æ¨¡æ¿çš„æ ¹
	            templateRoot: context.templateRoot || context.templateCurrentContext || template
	        }
	        // console.log('path:', context.path.join('.'), template)

	    var rule = Parser.parse(name)
	    var type = Util.type(template)
	    var data

	    if (Handler[type]) {
	        data = Handler[type]({
	            // å±žæ€§å€¼ç±»åž‹
	            type: type,
	            // å±žæ€§å€¼æ¨¡æ¿
	            template: template,
	            // å±žæ€§å + ç”Ÿæˆè§„åˆ™
	            name: name,
	            // å±žæ€§å
	            parsedName: name ? name.replace(Constant.RE_KEY, '$1') : name,

	            // è§£æžåŽçš„ç”Ÿæˆè§„åˆ™
	            rule: rule,
	            // ç›¸å…³ä¸Šä¸‹æ–‡
	            context: context
	        })

	        if (!context.root) context.root = data
	        return data
	    }

	    return template
	}

	Handler.extend({
	    array: function(options) {
	        var result = [],
	            i, ii;

	        // 'name|1': []
	        // 'name|count': []
	        // 'name|min-max': []
	        if (options.template.length === 0) return result

	        // 'arr': [{ 'email': '@EMAIL' }, { 'email': '@EMAIL' }]
	        if (!options.rule.parameters) {
	            for (i = 0; i < options.template.length; i++) {
	                options.context.path.push(i)
	                options.context.templatePath.push(i)
	                result.push(
	                    Handler.gen(options.template[i], i, {
	                        path: options.context.path,
	                        templatePath: options.context.templatePath,
	                        currentContext: result,
	                        templateCurrentContext: options.template,
	                        root: options.context.root || result,
	                        templateRoot: options.context.templateRoot || options.template
	                    })
	                )
	                options.context.path.pop()
	                options.context.templatePath.pop()
	            }
	        } else {
	            // 'method|1': ['GET', 'POST', 'HEAD', 'DELETE']
	            if (options.rule.min === 1 && options.rule.max === undefined) {
	                // fix #17
	                options.context.path.push(options.name)
	                options.context.templatePath.push(options.name)
	                result = Random.pick(
	                    Handler.gen(options.template, undefined, {
	                        path: options.context.path,
	                        templatePath: options.context.templatePath,
	                        currentContext: result,
	                        templateCurrentContext: options.template,
	                        root: options.context.root || result,
	                        templateRoot: options.context.templateRoot || options.template
	                    })
	                )
	                options.context.path.pop()
	                options.context.templatePath.pop()
	            } else {
	                // 'data|+1': [{}, {}]
	                if (options.rule.parameters[2]) {
	                    options.template.__order_index = options.template.__order_index || 0

	                    options.context.path.push(options.name)
	                    options.context.templatePath.push(options.name)
	                    result = Handler.gen(options.template, undefined, {
	                        path: options.context.path,
	                        templatePath: options.context.templatePath,
	                        currentContext: result,
	                        templateCurrentContext: options.template,
	                        root: options.context.root || result,
	                        templateRoot: options.context.templateRoot || options.template
	                    })[
	                        options.template.__order_index % options.template.length
	                    ]

	                    options.template.__order_index += +options.rule.parameters[2]

	                    options.context.path.pop()
	                    options.context.templatePath.pop()

	                } else {
	                    // 'data|1-10': [{}]
	                    for (i = 0; i < options.rule.count; i++) {
	                        // 'data|1-10': [{}, {}]
	                        for (ii = 0; ii < options.template.length; ii++) {
	                            options.context.path.push(result.length)
	                            options.context.templatePath.push(ii)
	                            result.push(
	                                Handler.gen(options.template[ii], result.length, {
	                                    path: options.context.path,
	                                    templatePath: options.context.templatePath,
	                                    currentContext: result,
	                                    templateCurrentContext: options.template,
	                                    root: options.context.root || result,
	                                    templateRoot: options.context.templateRoot || options.template
	                                })
	                            )
	                            options.context.path.pop()
	                            options.context.templatePath.pop()
	                        }
	                    }
	                }
	            }
	        }
	        return result
	    },
	    object: function(options) {
	        var result = {},
	            keys, fnKeys, key, parsedKey, inc, i;

	        // 'obj|min-max': {}
	        /* jshint -W041 */
	        if (options.rule.min != undefined) {
	            keys = Util.keys(options.template)
	            keys = Random.shuffle(keys)
	            keys = keys.slice(0, options.rule.count)
	            for (i = 0; i < keys.length; i++) {
	                key = keys[i]
	                parsedKey = key.replace(Constant.RE_KEY, '$1')
	                options.context.path.push(parsedKey)
	                options.context.templatePath.push(key)
	                result[parsedKey] = Handler.gen(options.template[key], key, {
	                    path: options.context.path,
	                    templatePath: options.context.templatePath,
	                    currentContext: result,
	                    templateCurrentContext: options.template,
	                    root: options.context.root || result,
	                    templateRoot: options.context.templateRoot || options.template
	                })
	                options.context.path.pop()
	                options.context.templatePath.pop()
	            }

	        } else {
	            // 'obj': {}
	            keys = []
	            fnKeys = [] // #25 æ”¹å˜äº†éžå‡½æ•°å±žæ€§çš„é¡ºåºï¼ŒæŸ¥æ‰¾èµ·æ¥ä¸æ–¹ä¾¿
	            for (key in options.template) {
	                (typeof options.template[key] === 'function' ? fnKeys : keys).push(key)
	            }
	            keys = keys.concat(fnKeys)

	            /*
	                ä¼šæ”¹å˜éžå‡½æ•°å±žæ€§çš„é¡ºåº
	                keys = Util.keys(options.template)
	                keys.sort(function(a, b) {
	                    var afn = typeof options.template[a] === 'function'
	                    var bfn = typeof options.template[b] === 'function'
	                    if (afn === bfn) return 0
	                    if (afn && !bfn) return 1
	                    if (!afn && bfn) return -1
	                })
	            */

	            for (i = 0; i < keys.length; i++) {
	                key = keys[i]
	                parsedKey = key.replace(Constant.RE_KEY, '$1')
	                options.context.path.push(parsedKey)
	                options.context.templatePath.push(key)
	                result[parsedKey] = Handler.gen(options.template[key], key, {
	                    path: options.context.path,
	                    templatePath: options.context.templatePath,
	                    currentContext: result,
	                    templateCurrentContext: options.template,
	                    root: options.context.root || result,
	                    templateRoot: options.context.templateRoot || options.template
	                })
	                options.context.path.pop()
	                options.context.templatePath.pop()
	                    // 'id|+1': 1
	                inc = key.match(Constant.RE_KEY)
	                if (inc && inc[2] && Util.type(options.template[key]) === 'number') {
	                    options.template[key] += parseInt(inc[2], 10)
	                }
	            }
	        }
	        return result
	    },
	    number: function(options) {
	        var result, parts;
	        if (options.rule.decimal) { // float
	            options.template += ''
	            parts = options.template.split('.')
	                // 'float1|.1-10': 10,
	                // 'float2|1-100.1-10': 1,
	                // 'float3|999.1-10': 1,
	                // 'float4|.3-10': 123.123,
	            parts[0] = options.rule.range ? options.rule.count : parts[0]
	            parts[1] = (parts[1] || '').slice(0, options.rule.dcount)
	            while (parts[1].length < options.rule.dcount) {
	                parts[1] += (
	                    // æœ€åŽä¸€ä½ä¸èƒ½ä¸º 0ï¼šå¦‚æžœæœ€åŽä¸€ä½ä¸º 0ï¼Œä¼šè¢« JS å¼•æ“Žå¿½ç•¥æŽ‰ã€‚
	                    (parts[1].length < options.rule.dcount - 1) ? Random.character('number') : Random.character('123456789')
	                )
	            }
	            result = parseFloat(parts.join('.'), 10)
	        } else { // integer
	            // 'grade1|1-100': 1,
	            result = options.rule.range && !options.rule.parameters[2] ? options.rule.count : options.template
	        }
	        return result
	    },
	    boolean: function(options) {
	        var result;
	        // 'prop|multiple': false, å½“å‰å€¼æ˜¯ç›¸åå€¼çš„æ¦‚çŽ‡å€æ•°
	        // 'prop|probability-probability': false, å½“å‰å€¼ä¸Žç›¸åå€¼çš„æ¦‚çŽ‡
	        result = options.rule.parameters ? Random.bool(options.rule.min, options.rule.max, options.template) : options.template
	        return result
	    },
	    string: function(options) {
	        var result = '',
	            i, placeholders, ph, phed;
	        if (options.template.length) {

	            //  'foo': 'â˜…',
	            /* jshint -W041 */
	            if (options.rule.count == undefined) {
	                result += options.template
	            }

	            // 'star|1-5': 'â˜…',
	            for (i = 0; i < options.rule.count; i++) {
	                result += options.template
	            }
	            // 'email|1-10': '@EMAIL, ',
	            placeholders = result.match(Constant.RE_PLACEHOLDER) || [] // A-Z_0-9 > \w_
	            for (i = 0; i < placeholders.length; i++) {
	                ph = placeholders[i]

	                // é‡åˆ°è½¬ä¹‰æ–œæ ï¼Œä¸éœ€è¦è§£æžå ä½ç¬¦
	                if (/^\\/.test(ph)) {
	                    placeholders.splice(i--, 1)
	                    continue
	                }

	                phed = Handler.placeholder(ph, options.context.currentContext, options.context.templateCurrentContext, options)

	                // åªæœ‰ä¸€ä¸ªå ä½ç¬¦ï¼Œå¹¶ä¸”æ²¡æœ‰å…¶ä»–å­—ç¬¦
	                if (placeholders.length === 1 && ph === result && typeof phed !== typeof result) { // 
	                    result = phed
	                    break

	                    if (Util.isNumeric(phed)) {
	                        result = parseFloat(phed, 10)
	                        break
	                    }
	                    if (/^(true|false)$/.test(phed)) {
	                        result = phed === 'true' ? true :
	                            phed === 'false' ? false :
	                            phed // å·²ç»æ˜¯å¸ƒå°”å€¼
	                        break
	                    }
	                }
	                result = result.replace(ph, phed)
	            }

	        } else {
	            // 'ASCII|1-10': '',
	            // 'ASCII': '',
	            result = options.rule.range ? Random.string(options.rule.count) : options.template
	        }
	        return result
	    },
	    'function': function(options) {
	        // ( context, options )
	        return options.template.call(options.context.currentContext, options)
	    },
	    'regexp': function(options) {
	        var source = ''

	        // 'name': /regexp/,
	        /* jshint -W041 */
	        if (options.rule.count == undefined) {
	            source += options.template.source // regexp.source
	        }

	        // 'name|1-5': /regexp/,
	        for (var i = 0; i < options.rule.count; i++) {
	            source += options.template.source
	        }

	        return RE.Handler.gen(
	            RE.Parser.parse(
	                source
	            )
	        )
	    }
	})

	Handler.extend({
	    _all: function() {
	        var re = {};
	        for (var key in Random) re[key.toLowerCase()] = key
	        return re
	    },
	    // å¤„ç†å ä½ç¬¦ï¼Œè½¬æ¢ä¸ºæœ€ç»ˆå€¼
	    placeholder: function(placeholder, obj, templateContext, options) {
	        // console.log(options.context.path)
	        // 1 key, 2 params
	        Constant.RE_PLACEHOLDER.exec('')
	        var parts = Constant.RE_PLACEHOLDER.exec(placeholder),
	            key = parts && parts[1],
	            lkey = key && key.toLowerCase(),
	            okey = this._all()[lkey],
	            params = parts && parts[2] || ''
	        var pathParts = this.splitPathToArray(key)

	        // è§£æžå ä½ç¬¦çš„å‚æ•°
	        try {
	            // 1. å°è¯•ä¿æŒå‚æ•°çš„ç±»åž‹
	            /*
	                #24 [Window Firefox 30.0 å¼•ç”¨ å ä½ç¬¦ æŠ›é”™](https://github.com/nuysoft/Mock/issues/24)
	                [BX9056: å„æµè§ˆå™¨ä¸‹ window.eval æ–¹æ³•çš„æ‰§è¡Œä¸Šä¸‹æ–‡å­˜åœ¨å·®å¼‚](http://www.w3help.org/zh-cn/causes/BX9056)
	                åº”è¯¥å±žäºŽ Window Firefox 30.0 çš„ BUG
	            */
	            /* jshint -W061 */
	            params = eval('(function(){ return [].splice.call(arguments, 0 ) })(' + params + ')')
	        } catch (error) {
	            // 2. å¦‚æžœå¤±è´¥ï¼Œåªèƒ½è§£æžä¸ºå­—ç¬¦ä¸²
	            // console.error(error)
	            // if (error instanceof ReferenceError) params = parts[2].split(/,\s*/);
	            // else throw error
	            params = parts[2].split(/,\s*/)
	        }

	        // å ä½ç¬¦ä¼˜å…ˆå¼•ç”¨æ•°æ®æ¨¡æ¿ä¸­çš„å±žæ€§
	        if (obj && (key in obj)) return obj[key]

	        // @index @key
	        // if (Constant.RE_INDEX.test(key)) return +options.name
	        // if (Constant.RE_KEY.test(key)) return options.name

	        // ç»å¯¹è·¯å¾„ or ç›¸å¯¹è·¯å¾„
	        if (
	            key.charAt(0) === '/' ||
	            pathParts.length > 1
	        ) return this.getValueByKeyPath(key, options)

	        // é€’å½’å¼•ç”¨æ•°æ®æ¨¡æ¿ä¸­çš„å±žæ€§
	        if (templateContext &&
	            (typeof templateContext === 'object') &&
	            (key in templateContext) &&
	            (placeholder !== templateContext[key]) // fix #15 é¿å…è‡ªå·±ä¾èµ–è‡ªå·±
	        ) {
	            // å…ˆè®¡ç®—è¢«å¼•ç”¨çš„å±žæ€§å€¼
	            templateContext[key] = Handler.gen(templateContext[key], key, {
	                currentContext: obj,
	                templateCurrentContext: templateContext
	            })
	            return templateContext[key]
	        }

	        // å¦‚æžœæœªæ‰¾åˆ°ï¼Œåˆ™åŽŸæ ·è¿”å›ž
	        if (!(key in Random) && !(lkey in Random) && !(okey in Random)) return placeholder

	        // é€’å½’è§£æžå‚æ•°ä¸­çš„å ä½ç¬¦
	        for (var i = 0; i < params.length; i++) {
	            Constant.RE_PLACEHOLDER.exec('')
	            if (Constant.RE_PLACEHOLDER.test(params[i])) {
	                params[i] = Handler.placeholder(params[i], obj, templateContext, options)
	            }
	        }

	        var handle = Random[key] || Random[lkey] || Random[okey]
	        switch (Util.type(handle)) {
	            case 'array':
	                // è‡ªåŠ¨ä»Žæ•°ç»„ä¸­å–ä¸€ä¸ªï¼Œä¾‹å¦‚ @areas
	                return Random.pick(handle)
	            case 'function':
	                // æ‰§è¡Œå ä½ç¬¦æ–¹æ³•ï¼ˆå¤§å¤šæ•°æƒ…å†µï¼‰
	                handle.options = options
	                var re = handle.apply(Random, params)
	                if (re === undefined) re = '' // å› ä¸ºæ˜¯åœ¨å­—ç¬¦ä¸²ä¸­ï¼Œæ‰€ä»¥é»˜è®¤ä¸ºç©ºå­—ç¬¦ä¸²ã€‚
	                delete handle.options
	                return re
	        }
	    },
	    getValueByKeyPath: function(key, options) {
	        var originalKey = key
	        var keyPathParts = this.splitPathToArray(key)
	        var absolutePathParts = []

	        // ç»å¯¹è·¯å¾„
	        if (key.charAt(0) === '/') {
	            absolutePathParts = [options.context.path[0]].concat(
	                this.normalizePath(keyPathParts)
	            )
	        } else {
	            // ç›¸å¯¹è·¯å¾„
	            if (keyPathParts.length > 1) {
	                absolutePathParts = options.context.path.slice(0)
	                absolutePathParts.pop()
	                absolutePathParts = this.normalizePath(
	                    absolutePathParts.concat(keyPathParts)
	                )

	            }
	        }

	        key = keyPathParts[keyPathParts.length - 1]
	        var currentContext = options.context.root
	        var templateCurrentContext = options.context.templateRoot
	        for (var i = 1; i < absolutePathParts.length - 1; i++) {
	            currentContext = currentContext[absolutePathParts[i]]
	            templateCurrentContext = templateCurrentContext[absolutePathParts[i]]
	        }
	        // å¼•ç”¨çš„å€¼å·²ç»è®¡ç®—å¥½
	        if (currentContext && (key in currentContext)) return currentContext[key]

	        // å°šæœªè®¡ç®—ï¼Œé€’å½’å¼•ç”¨æ•°æ®æ¨¡æ¿ä¸­çš„å±žæ€§
	        if (templateCurrentContext &&
	            (typeof templateCurrentContext === 'object') &&
	            (key in templateCurrentContext) &&
	            (originalKey !== templateCurrentContext[key]) // fix #15 é¿å…è‡ªå·±ä¾èµ–è‡ªå·±
	        ) {
	            // å…ˆè®¡ç®—è¢«å¼•ç”¨çš„å±žæ€§å€¼
	            templateCurrentContext[key] = Handler.gen(templateCurrentContext[key], key, {
	                currentContext: currentContext,
	                templateCurrentContext: templateCurrentContext
	            })
	            return templateCurrentContext[key]
	        }
	    },
	    // https://github.com/kissyteam/kissy/blob/master/src/path/src/path.js
	    normalizePath: function(pathParts) {
	        var newPathParts = []
	        for (var i = 0; i < pathParts.length; i++) {
	            switch (pathParts[i]) {
	                case '..':
	                    newPathParts.pop()
	                    break
	                case '.':
	                    break
	                default:
	                    newPathParts.push(pathParts[i])
	            }
	        }
	        return newPathParts
	    },
	    splitPathToArray: function(path) {
	        var parts = path.split(/\/+/);
	        if (!parts[parts.length - 1]) parts = parts.slice(0, -1)
	        if (!parts[0]) parts = parts.slice(1)
	        return parts;
	    }
	})

	module.exports = Handler

/***/ },
/* 2 */
/***/ function(module, exports) {

	/*
	    ## Constant

	    å¸¸é‡é›†åˆã€‚
	 */
	/*
	    RE_KEY
	        'name|min-max': value
	        'name|count': value
	        'name|min-max.dmin-dmax': value
	        'name|min-max.dcount': value
	        'name|count.dmin-dmax': value
	        'name|count.dcount': value
	        'name|+step': value

	        1 name, 2 step, 3 range [ min, max ], 4 drange [ dmin, dmax ]

	    RE_PLACEHOLDER
	        placeholder(*)

	    [æ­£åˆ™æŸ¥çœ‹å·¥å…·](http://www.regexper.com/)

	    #26 ç”Ÿæˆè§„åˆ™ æ”¯æŒ è´Ÿæ•°ï¼Œä¾‹å¦‚ number|-100-100
	*/
	module.exports = {
	    GUID: 1,
	    RE_KEY: /(.+)\|(?:\+(\d+)|([\+\-]?\d+-?[\+\-]?\d*)?(?:\.(\d+-?\d*))?)/,
	    RE_RANGE: /([\+\-]?\d+)-?([\+\-]?\d+)?/,
	    RE_PLACEHOLDER: /\\*@([^@#%&()\?\s]+)(?:\((.*?)\))?/g
	    // /\\*@([^@#%&()\?\s\/\.]+)(?:\((.*?)\))?/g
	    // RE_INDEX: /^index$/,
	    // RE_KEY: /^key$/
	}

/***/ },
/* 3 */
/***/ function(module, exports) {

	/*
	    ## Utilities
	*/
	var Util = {}

	Util.extend = function extend() {
	    var target = arguments[0] || {},
	        i = 1,
	        length = arguments.length,
	        options, name, src, copy, clone

	    if (length === 1) {
	        target = this
	        i = 0
	    }

	    for (; i < length; i++) {
	        options = arguments[i]
	        if (!options) continue

	        for (name in options) {
	            src = target[name]
	            copy = options[name]

	            if (target === copy) continue
	            if (copy === undefined) continue

	            if (Util.isArray(copy) || Util.isObject(copy)) {
	                if (Util.isArray(copy)) clone = src && Util.isArray(src) ? src : []
	                if (Util.isObject(copy)) clone = src && Util.isObject(src) ? src : {}

	                target[name] = Util.extend(clone, copy)
	            } else {
	                target[name] = copy
	            }
	        }
	    }

	    return target
	}

	Util.each = function each(obj, iterator, context) {
	    var i, key
	    if (this.type(obj) === 'number') {
	        for (i = 0; i < obj; i++) {
	            iterator(i, i)
	        }
	    } else if (obj.length === +obj.length) {
	        for (i = 0; i < obj.length; i++) {
	            if (iterator.call(context, obj[i], i, obj) === false) break
	        }
	    } else {
	        for (key in obj) {
	            if (iterator.call(context, obj[key], key, obj) === false) break
	        }
	    }
	}

	Util.type = function type(obj) {
	    return (obj === null || obj === undefined) ? String(obj) : Object.prototype.toString.call(obj).match(/\[object (\w+)\]/)[1].toLowerCase()
	}

	Util.each('String Object Array RegExp Function'.split(' '), function(value) {
	    Util['is' + value] = function(obj) {
	        return Util.type(obj) === value.toLowerCase()
	    }
	})

	Util.isObjectOrArray = function(value) {
	    return Util.isObject(value) || Util.isArray(value)
	}

	Util.isNumeric = function(value) {
	    return !isNaN(parseFloat(value)) && isFinite(value)
	}

	Util.keys = function(obj) {
	    var keys = [];
	    for (var key in obj) {
	        if (obj.hasOwnProperty(key)) keys.push(key)
	    }
	    return keys;
	}
	Util.values = function(obj) {
	    var values = [];
	    for (var key in obj) {
	        if (obj.hasOwnProperty(key)) values.push(obj[key])
	    }
	    return values;
	}

	/*
	    ### Mock.heredoc(fn)

	    * Mock.heredoc(fn)

	    ä»¥ç›´è§‚ã€å®‰å…¨çš„æ–¹å¼ä¹¦å†™ï¼ˆå¤šè¡Œï¼‰HTML æ¨¡æ¿ã€‚

	    **ä½¿ç”¨ç¤ºä¾‹**å¦‚ä¸‹æ‰€ç¤ºï¼š

	        var tpl = Mock.heredoc(function() {
	            /*!
	        {{email}}{{age}}
	        <!-- Mock { 
	            email: '@EMAIL',
	            age: '@INT(1,100)'
	        } -->
	            *\/
	        })
	    
	    **ç›¸å…³é˜…è¯»**
	    * [Creating multiline strings in JavaScript](http://stackoverflow.com/questions/805107/creating-multiline-strings-in-javascript)ã€
	*/
	Util.heredoc = function heredoc(fn) {
	    // 1. ç§»é™¤èµ·å§‹çš„ function(){ /*!
	    // 2. ç§»é™¤æœ«å°¾çš„ */ }
	    // 3. ç§»é™¤èµ·å§‹å’Œæœ«å°¾çš„ç©ºæ ¼
	    return fn.toString()
	        .replace(/^[^\/]+\/\*!?/, '')
	        .replace(/\*\/[^\/]+$/, '')
	        .replace(/^[\s\xA0]+/, '').replace(/[\s\xA0]+$/, '') // .trim()
	}

	Util.noop = function() {}

	module.exports = Util

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	/*
		## Parser

		è§£æžæ•°æ®æ¨¡æ¿ï¼ˆå±žæ€§åéƒ¨åˆ†ï¼‰ã€‚

		* Parser.parse( name )
			
			```json
			{
				parameters: [ name, inc, range, decimal ],
				rnage: [ min , max ],

				min: min,
				max: max,
				count : count,

				decimal: decimal,
				dmin: dmin,
				dmax: dmax,
				dcount: dcount
			}
			```
	 */

	var Constant = __webpack_require__(2)
	var Random = __webpack_require__(5)

	/* jshint -W041 */
	module.exports = {
		parse: function(name) {
			name = name == undefined ? '' : (name + '')

			var parameters = (name || '').match(Constant.RE_KEY)

			var range = parameters && parameters[3] && parameters[3].match(Constant.RE_RANGE)
			var min = range && range[1] && parseInt(range[1], 10) // || 1
			var max = range && range[2] && parseInt(range[2], 10) // || 1
				// repeat || min-max || 1
				// var count = range ? !range[2] && parseInt(range[1], 10) || Random.integer(min, max) : 1
			var count = range ? !range[2] ? parseInt(range[1], 10) : Random.integer(min, max) : undefined

			var decimal = parameters && parameters[4] && parameters[4].match(Constant.RE_RANGE)
			var dmin = decimal && parseInt(decimal[1], 10) // || 0,
			var dmax = decimal && parseInt(decimal[2], 10) // || 0,
				// int || dmin-dmax || 0
			var dcount = decimal ? !decimal[2] && parseInt(decimal[1], 10) || Random.integer(dmin, dmax) : undefined

			var result = {
				// 1 name, 2 inc, 3 range, 4 decimal
				parameters: parameters,
				// 1 min, 2 max
				range: range,
				min: min,
				max: max,
				// min-max
				count: count,
				// æ˜¯å¦æœ‰ decimal
				decimal: decimal,
				dmin: dmin,
				dmax: dmax,
				// dmin-dimax
				dcount: dcount
			}

			for (var r in result) {
				if (result[r] != undefined) return result
			}

			return {}
		}
	}

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Mock.Random
	    
	    å·¥å…·ç±»ï¼Œç”¨äºŽç”Ÿæˆå„ç§éšæœºæ•°æ®ã€‚
	*/

	var Util = __webpack_require__(3)

	var Random = {
	    extend: Util.extend
	}

	Random.extend(__webpack_require__(6))
	Random.extend(__webpack_require__(7))
	Random.extend(__webpack_require__(8))
	Random.extend(__webpack_require__(10))
	Random.extend(__webpack_require__(13))
	Random.extend(__webpack_require__(15))
	Random.extend(__webpack_require__(16))
	Random.extend(__webpack_require__(17))
	Random.extend(__webpack_require__(14))
	Random.extend(__webpack_require__(19))

	module.exports = Random

/***/ },
/* 6 */
/***/ function(module, exports) {

	/*
	    ## Basics
	*/
	module.exports = {
	    // è¿”å›žä¸€ä¸ªéšæœºçš„å¸ƒå°”å€¼ã€‚
	    boolean: function(min, max, cur) {
	        if (cur !== undefined) {
	            min = typeof min !== 'undefined' && !isNaN(min) ? parseInt(min, 10) : 1
	            max = typeof max !== 'undefined' && !isNaN(max) ? parseInt(max, 10) : 1
	            return Math.random() > 1.0 / (min + max) * min ? !cur : cur
	        }

	        return Math.random() >= 0.5
	    },
	    bool: function(min, max, cur) {
	        return this.boolean(min, max, cur)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„è‡ªç„¶æ•°ï¼ˆå¤§äºŽç­‰äºŽ 0 çš„æ•´æ•°ï¼‰ã€‚
	    natural: function(min, max) {
	        min = typeof min !== 'undefined' ? parseInt(min, 10) : 0
	        max = typeof max !== 'undefined' ? parseInt(max, 10) : 9007199254740992 // 2^53
	        return Math.round(Math.random() * (max - min)) + min
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„æ•´æ•°ã€‚
	    integer: function(min, max) {
	        min = typeof min !== 'undefined' ? parseInt(min, 10) : -9007199254740992
	        max = typeof max !== 'undefined' ? parseInt(max, 10) : 9007199254740992 // 2^53
	        return Math.round(Math.random() * (max - min)) + min
	    },
	    int: function(min, max) {
	        return this.integer(min, max)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„æµ®ç‚¹æ•°ã€‚
	    float: function(min, max, dmin, dmax) {
	        dmin = dmin === undefined ? 0 : dmin
	        dmin = Math.max(Math.min(dmin, 17), 0)
	        dmax = dmax === undefined ? 17 : dmax
	        dmax = Math.max(Math.min(dmax, 17), 0)
	        var ret = this.integer(min, max) + '.';
	        for (var i = 0, dcount = this.natural(dmin, dmax); i < dcount; i++) {
	            ret += (
	                // æœ€åŽä¸€ä½ä¸èƒ½ä¸º 0ï¼šå¦‚æžœæœ€åŽä¸€ä½ä¸º 0ï¼Œä¼šè¢« JS å¼•æ“Žå¿½ç•¥æŽ‰ã€‚
	                (i < dcount - 1) ? this.character('number') : this.character('123456789')
	            )
	        }
	        return parseFloat(ret, 10)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºå­—ç¬¦ã€‚
	    character: function(pool) {
	        var pools = {
	            lower: 'abcdefghijklmnopqrstuvwxyz',
	            upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
	            number: '0123456789',
	            symbol: '!@#$%^&*()[]'
	        }
	        pools.alpha = pools.lower + pools.upper
	        pools['undefined'] = pools.lower + pools.upper + pools.number + pools.symbol

	        pool = pools[('' + pool).toLowerCase()] || pool
	        return pool.charAt(this.natural(0, pool.length - 1))
	    },
	    char: function(pool) {
	        return this.character(pool)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºå­—ç¬¦ä¸²ã€‚
	    string: function(pool, min, max) {
	        var len
	        switch (arguments.length) {
	            case 0: // ()
	                len = this.natural(3, 7)
	                break
	            case 1: // ( length )
	                len = pool
	                pool = undefined
	                break
	            case 2:
	                // ( pool, length )
	                if (typeof arguments[0] === 'string') {
	                    len = min
	                } else {
	                    // ( min, max )
	                    len = this.natural(pool, min)
	                    pool = undefined
	                }
	                break
	            case 3:
	                len = this.natural(min, max)
	                break
	        }

	        var text = ''
	        for (var i = 0; i < len; i++) {
	            text += this.character(pool)
	        }

	        return text
	    },
	    str: function( /*pool, min, max*/ ) {
	        return this.string.apply(this, arguments)
	    },
	    // è¿”å›žä¸€ä¸ªæ•´åž‹æ•°ç»„ã€‚
	    range: function(start, stop, step) {
	        // range( stop )
	        if (arguments.length <= 1) {
	            stop = start || 0;
	            start = 0;
	        }
	        // range( start, stop )
	        step = arguments[2] || 1;

	        start = +start
	        stop = +stop
	        step = +step

	        var len = Math.max(Math.ceil((stop - start) / step), 0);
	        var idx = 0;
	        var range = new Array(len);

	        while (idx < len) {
	            range[idx++] = start;
	            start += step;
	        }

	        return range;
	    }
	}

/***/ },
/* 7 */
/***/ function(module, exports) {

	/*
	    ## Date
	*/
	var patternLetters = {
	    yyyy: 'getFullYear',
	    yy: function(date) {
	        return ('' + date.getFullYear()).slice(2)
	    },
	    y: 'yy',

	    MM: function(date) {
	        var m = date.getMonth() + 1
	        return m < 10 ? '0' + m : m
	    },
	    M: function(date) {
	        return date.getMonth() + 1
	    },

	    dd: function(date) {
	        var d = date.getDate()
	        return d < 10 ? '0' + d : d
	    },
	    d: 'getDate',

	    HH: function(date) {
	        var h = date.getHours()
	        return h < 10 ? '0' + h : h
	    },
	    H: 'getHours',
	    hh: function(date) {
	        var h = date.getHours() % 12
	        return h < 10 ? '0' + h : h
	    },
	    h: function(date) {
	        return date.getHours() % 12
	    },

	    mm: function(date) {
	        var m = date.getMinutes()
	        return m < 10 ? '0' + m : m
	    },
	    m: 'getMinutes',

	    ss: function(date) {
	        var s = date.getSeconds()
	        return s < 10 ? '0' + s : s
	    },
	    s: 'getSeconds',

	    SS: function(date) {
	        var ms = date.getMilliseconds()
	        return ms < 10 && '00' + ms || ms < 100 && '0' + ms || ms
	    },
	    S: 'getMilliseconds',

	    A: function(date) {
	        return date.getHours() < 12 ? 'AM' : 'PM'
	    },
	    a: function(date) {
	        return date.getHours() < 12 ? 'am' : 'pm'
	    },
	    T: 'getTime'
	}
	module.exports = {
	    // æ—¥æœŸå ä½ç¬¦é›†åˆã€‚
	    _patternLetters: patternLetters,
	    // æ—¥æœŸå ä½ç¬¦æ­£åˆ™ã€‚
	    _rformat: new RegExp((function() {
	        var re = []
	        for (var i in patternLetters) re.push(i)
	        return '(' + re.join('|') + ')'
	    })(), 'g'),
	    // æ ¼å¼åŒ–æ—¥æœŸã€‚
	    _formatDate: function(date, format) {
	        return format.replace(this._rformat, function creatNewSubString($0, flag) {
	            return typeof patternLetters[flag] === 'function' ? patternLetters[flag](date) :
	                patternLetters[flag] in patternLetters ? creatNewSubString($0, patternLetters[flag]) :
	                date[patternLetters[flag]]()
	        })
	    },
	    // ç”Ÿæˆä¸€ä¸ªéšæœºçš„ Date å¯¹è±¡ã€‚
	    _randomDate: function(min, max) { // min, max
	        min = min === undefined ? new Date(0) : min
	        max = max === undefined ? new Date() : max
	        return new Date(Math.random() * (max.getTime() - min.getTime()))
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„æ—¥æœŸå­—ç¬¦ä¸²ã€‚
	    date: function(format) {
	        format = format || 'yyyy-MM-dd'
	        return this._formatDate(this._randomDate(), format)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„æ—¶é—´å­—ç¬¦ä¸²ã€‚
	    time: function(format) {
	        format = format || 'HH:mm:ss'
	        return this._formatDate(this._randomDate(), format)
	    },
	    // è¿”å›žä¸€ä¸ªéšæœºçš„æ—¥æœŸå’Œæ—¶é—´å­—ç¬¦ä¸²ã€‚
	    datetime: function(format) {
	        format = format || 'yyyy-MM-dd HH:mm:ss'
	        return this._formatDate(this._randomDate(), format)
	    },
	    // è¿”å›žå½“å‰çš„æ—¥æœŸå’Œæ—¶é—´å­—ç¬¦ä¸²ã€‚
	    now: function(unit, format) {
	        // now(unit) now(format)
	        if (arguments.length === 1) {
	            // now(format)
	            if (!/year|month|day|hour|minute|second|week/.test(unit)) {
	                format = unit
	                unit = ''
	            }
	        }
	        unit = (unit || '').toLowerCase()
	        format = format || 'yyyy-MM-dd HH:mm:ss'

	        var date = new Date()

	        /* jshint -W086 */
	        // å‚è€ƒè‡ª http://momentjs.cn/docs/#/manipulating/start-of/
	        switch (unit) {
	            case 'year':
	                date.setMonth(0)
	            case 'month':
	                date.setDate(1)
	            case 'week':
	            case 'day':
	                date.setHours(0)
	            case 'hour':
	                date.setMinutes(0)
	            case 'minute':
	                date.setSeconds(0)
	            case 'second':
	                date.setMilliseconds(0)
	        }
	        switch (unit) {
	            case 'week':
	                date.setDate(date.getDate() - date.getDay())
	        }

	        return this._formatDate(date, format)
	    }
	}

/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(module) {/* global document  */
	/*
	    ## Image
	*/
	module.exports = {
	    // å¸¸è§çš„å¹¿å‘Šå®½é«˜
	    _adSize: [
	        '300x250', '250x250', '240x400', '336x280', '180x150',
	        '720x300', '468x60', '234x60', '88x31', '120x90',
	        '120x60', '120x240', '125x125', '728x90', '160x600',
	        '120x600', '300x600'
	    ],
	    // å¸¸è§çš„å±å¹•å®½é«˜
	    _screenSize: [
	        '320x200', '320x240', '640x480', '800x480', '800x480',
	        '1024x600', '1024x768', '1280x800', '1440x900', '1920x1200',
	        '2560x1600'
	    ],
	    // å¸¸è§çš„è§†é¢‘å®½é«˜
	    _videoSize: ['720x480', '768x576', '1280x720', '1920x1080'],
	    /*
	        ç”Ÿæˆä¸€ä¸ªéšæœºçš„å›¾ç‰‡åœ°å€ã€‚

	        æ›¿ä»£å›¾ç‰‡æº
	            http://fpoimg.com/
	        å‚è€ƒè‡ª 
	            http://rensanning.iteye.com/blog/1933310
	            http://code.tutsplus.com/articles/the-top-8-placeholders-for-web-designers--net-19485
	    */
	    image: function(size, background, foreground, format, text) {
	        // Random.image( size, background, foreground, text )
	        if (arguments.length === 4) {
	            text = format
	            format = undefined
	        }
	        // Random.image( size, background, text )
	        if (arguments.length === 3) {
	            text = foreground
	            foreground = undefined
	        }
	        // Random.image()
	        if (!size) size = this.pick(this._adSize)

	        if (background && ~background.indexOf('#')) background = background.slice(1)
	        if (foreground && ~foreground.indexOf('#')) foreground = foreground.slice(1)

	        // http://dummyimage.com/600x400/cc00cc/470047.png&text=hello
	        return 'http://dummyimage.com/' + size +
	            (background ? '/' + background : '') +
	            (foreground ? '/' + foreground : '') +
	            (format ? '.' + format : '') +
	            (text ? '&text=' + text : '')
	    },
	    img: function() {
	        return this.image.apply(this, arguments)
	    },

	    /*
	        BrandColors
	        http://brandcolors.net/
	        A collection of major brand color codes curated by Galen Gidman.
	        å¤§ç‰Œå…¬å¸çš„é¢œè‰²é›†åˆ

	        // èŽ·å–å“ç‰Œå’Œé¢œè‰²
	        $('h2').each(function(index, item){
	            item = $(item)
	            console.log('\'' + item.text() + '\'', ':', '\'' + item.next().text() + '\'', ',')
	        })
	    */
	    _brandColors: {
	        '4ormat': '#fb0a2a',
	        '500px': '#02adea',
	        'About.me (blue)': '#00405d',
	        'About.me (yellow)': '#ffcc33',
	        'Addvocate': '#ff6138',
	        'Adobe': '#ff0000',
	        'Aim': '#fcd20b',
	        'Amazon': '#e47911',
	        'Android': '#a4c639',
	        'Angie\'s List': '#7fbb00',
	        'AOL': '#0060a3',
	        'Atlassian': '#003366',
	        'Behance': '#053eff',
	        'Big Cartel': '#97b538',
	        'bitly': '#ee6123',
	        'Blogger': '#fc4f08',
	        'Boeing': '#0039a6',
	        'Booking.com': '#003580',
	        'Carbonmade': '#613854',
	        'Cheddar': '#ff7243',
	        'Code School': '#3d4944',
	        'Delicious': '#205cc0',
	        'Dell': '#3287c1',
	        'Designmoo': '#e54a4f',
	        'Deviantart': '#4e6252',
	        'Designer News': '#2d72da',
	        'Devour': '#fd0001',
	        'DEWALT': '#febd17',
	        'Disqus (blue)': '#59a3fc',
	        'Disqus (orange)': '#db7132',
	        'Dribbble': '#ea4c89',
	        'Dropbox': '#3d9ae8',
	        'Drupal': '#0c76ab',
	        'Dunked': '#2a323a',
	        'eBay': '#89c507',
	        'Ember': '#f05e1b',
	        'Engadget': '#00bdf6',
	        'Envato': '#528036',
	        'Etsy': '#eb6d20',
	        'Evernote': '#5ba525',
	        'Fab.com': '#dd0017',
	        'Facebook': '#3b5998',
	        'Firefox': '#e66000',
	        'Flickr (blue)': '#0063dc',
	        'Flickr (pink)': '#ff0084',
	        'Forrst': '#5b9a68',
	        'Foursquare': '#25a0ca',
	        'Garmin': '#007cc3',
	        'GetGlue': '#2d75a2',
	        'Gimmebar': '#f70078',
	        'GitHub': '#171515',
	        'Google Blue': '#0140ca',
	        'Google Green': '#16a61e',
	        'Google Red': '#dd1812',
	        'Google Yellow': '#fcca03',
	        'Google+': '#dd4b39',
	        'Grooveshark': '#f77f00',
	        'Groupon': '#82b548',
	        'Hacker News': '#ff6600',
	        'HelloWallet': '#0085ca',
	        'Heroku (light)': '#c7c5e6',
	        'Heroku (dark)': '#6567a5',
	        'HootSuite': '#003366',
	        'Houzz': '#73ba37',
	        'HTML5': '#ec6231',
	        'IKEA': '#ffcc33',
	        'IMDb': '#f3ce13',
	        'Instagram': '#3f729b',
	        'Intel': '#0071c5',
	        'Intuit': '#365ebf',
	        'Kickstarter': '#76cc1e',
	        'kippt': '#e03500',
	        'Kodery': '#00af81',
	        'LastFM': '#c3000d',
	        'LinkedIn': '#0e76a8',
	        'Livestream': '#cf0005',
	        'Lumo': '#576396',
	        'Mixpanel': '#a086d3',
	        'Meetup': '#e51937',
	        'Nokia': '#183693',
	        'NVIDIA': '#76b900',
	        'Opera': '#cc0f16',
	        'Path': '#e41f11',
	        'PayPal (dark)': '#1e477a',
	        'PayPal (light)': '#3b7bbf',
	        'Pinboard': '#0000e6',
	        'Pinterest': '#c8232c',
	        'PlayStation': '#665cbe',
	        'Pocket': '#ee4056',
	        'Prezi': '#318bff',
	        'Pusha': '#0f71b4',
	        'Quora': '#a82400',
	        'QUOTE.fm': '#66ceff',
	        'Rdio': '#008fd5',
	        'Readability': '#9c0000',
	        'Red Hat': '#cc0000',
	        'Resource': '#7eb400',
	        'Rockpack': '#0ba6ab',
	        'Roon': '#62b0d9',
	        'RSS': '#ee802f',
	        'Salesforce': '#1798c1',
	        'Samsung': '#0c4da2',
	        'Shopify': '#96bf48',
	        'Skype': '#00aff0',
	        'Snagajob': '#f47a20',
	        'Softonic': '#008ace',
	        'SoundCloud': '#ff7700',
	        'Space Box': '#f86960',
	        'Spotify': '#81b71a',
	        'Sprint': '#fee100',
	        'Squarespace': '#121212',
	        'StackOverflow': '#ef8236',
	        'Staples': '#cc0000',
	        'Status Chart': '#d7584f',
	        'Stripe': '#008cdd',
	        'StudyBlue': '#00afe1',
	        'StumbleUpon': '#f74425',
	        'T-Mobile': '#ea0a8e',
	        'Technorati': '#40a800',
	        'The Next Web': '#ef4423',
	        'Treehouse': '#5cb868',
	        'Trulia': '#5eab1f',
	        'Tumblr': '#34526f',
	        'Twitch.tv': '#6441a5',
	        'Twitter': '#00acee',
	        'TYPO3': '#ff8700',
	        'Ubuntu': '#dd4814',
	        'Ustream': '#3388ff',
	        'Verizon': '#ef1d1d',
	        'Vimeo': '#86c9ef',
	        'Vine': '#00a478',
	        'Virb': '#06afd8',
	        'Virgin Media': '#cc0000',
	        'Wooga': '#5b009c',
	        'WordPress (blue)': '#21759b',
	        'WordPress (orange)': '#d54e21',
	        'WordPress (grey)': '#464646',
	        'Wunderlist': '#2b88d9',
	        'XBOX': '#9bc848',
	        'XING': '#126567',
	        'Yahoo!': '#720e9e',
	        'Yandex': '#ffcc00',
	        'Yelp': '#c41200',
	        'YouTube': '#c4302b',
	        'Zalongo': '#5498dc',
	        'Zendesk': '#78a300',
	        'Zerply': '#9dcc7a',
	        'Zootool': '#5e8b1d'
	    },
	    _brandNames: function() {
	        var brands = [];
	        for (var b in this._brandColors) {
	            brands.push(b)
	        }
	        return brands
	    },
	    /*
	        ç”Ÿæˆä¸€æ®µéšæœºçš„ Base64 å›¾ç‰‡ç¼–ç ã€‚

	        https://github.com/imsky/holder
	        Holder renders image placeholders entirely on the client side.

	        dataImageHolder: function(size) {
	            return 'holder.js/' + size
	        },
	    */
	    dataImage: function(size, text) {
	        var canvas
	        if (typeof document !== 'undefined') {
	            canvas = document.createElement('canvas')
	        } else {
	            /*
	                https://github.com/Automattic/node-canvas
	                    npm install canvas --save
	                å®‰è£…é—®é¢˜ï¼š
	                * http://stackoverflow.com/questions/22953206/gulp-issues-with-cario-install-command-not-found-when-trying-to-installing-canva
	                * https://github.com/Automattic/node-canvas/issues/415
	                * https://github.com/Automattic/node-canvas/wiki/_pages

	                PSï¼šnode-canvas çš„å®‰è£…è¿‡ç¨‹å®žåœ¨æ˜¯å¤ªç¹çäº†ï¼Œæ‰€ä»¥ä¸æ”¾å…¥ package.json çš„ dependenciesã€‚
	             */
	            var Canvas = module.require('canvas')
	            canvas = new Canvas()
	        }

	        var ctx = canvas && canvas.getContext && canvas.getContext("2d")
	        if (!canvas || !ctx) return ''

	        if (!size) size = this.pick(this._adSize)
	        text = text !== undefined ? text : size

	        size = size.split('x')

	        var width = parseInt(size[0], 10),
	            height = parseInt(size[1], 10),
	            background = this._brandColors[this.pick(this._brandNames())],
	            foreground = '#FFF',
	            text_height = 14,
	            font = 'sans-serif';

	        canvas.width = width
	        canvas.height = height
	        ctx.textAlign = 'center'
	        ctx.textBaseline = 'middle'
	        ctx.fillStyle = background
	        ctx.fillRect(0, 0, width, height)
	        ctx.fillStyle = foreground
	        ctx.font = 'bold ' + text_height + 'px ' + font
	        ctx.fillText(text, (width / 2), (height / 2), width)
	        return canvas.toDataURL('image/png')
	    }
	}
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(9)(module)))

/***/ },
/* 9 */
/***/ function(module, exports) {

	module.exports = function(module) {
		if(!module.webpackPolyfill) {
			module.deprecate = function() {};
			module.paths = [];
			// module.parent = undefined by default
			module.children = [];
			module.webpackPolyfill = 1;
		}
		return module;
	}


/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Color

	    http://llllll.li/randomColor/
	        A color generator for JavaScript.
	        randomColor generates attractive colors by default. More specifically, randomColor produces bright colors with a reasonably high saturation. This makes randomColor particularly useful for data visualizations and generative art.

	    http://randomcolour.com/
	        var bg_colour = Math.floor(Math.random() * 16777215).toString(16);
	        bg_colour = "#" + ("000000" + bg_colour).slice(-6);
	        document.bgColor = bg_colour;
	    
	    http://martin.ankerl.com/2009/12/09/how-to-create-random-colors-programmatically/
	        Creating random colors is actually more difficult than it seems. The randomness itself is easy, but aesthetically pleasing randomness is more difficult.
	        https://github.com/devongovett/color-generator

	    http://www.paulirish.com/2009/random-hex-color-code-snippets/
	        Random Hex Color Code Generator in JavaScript

	    http://chancejs.com/#color
	        chance.color()
	        // => '#79c157'
	        chance.color({format: 'hex'})
	        // => '#d67118'
	        chance.color({format: 'shorthex'})
	        // => '#60f'
	        chance.color({format: 'rgb'})
	        // => 'rgb(110,52,164)'

	    http://tool.c7sky.com/webcolor
	        ç½‘é¡µè®¾è®¡å¸¸ç”¨è‰²å½©æ­é…è¡¨
	    
	    https://github.com/One-com/one-color
	        An OO-based JavaScript color parser/computation toolkit with support for RGB, HSV, HSL, CMYK, and alpha channels.
	        API å¾ˆèµž

	    https://github.com/harthur/color
	        JavaScript color conversion and manipulation library

	    https://github.com/leaverou/css-colors
	        Share & convert CSS colors
	    http://leaverou.github.io/css-colors/#slategray
	        Type a CSS color keyword, #hex, hsl(), rgba(), whatever:

	    è‰²è°ƒ hue
	        http://baike.baidu.com/view/23368.htm
	        è‰²è°ƒæŒ‡çš„æ˜¯ä¸€å¹…ç”»ä¸­ç”»é¢è‰²å½©çš„æ€»ä½“å€¾å‘ï¼Œæ˜¯å¤§çš„è‰²å½©æ•ˆæžœã€‚
	    é¥±å’Œåº¦ saturation
	        http://baike.baidu.com/view/189644.htm
	        é¥±å’Œåº¦æ˜¯æŒ‡è‰²å½©çš„é²œè‰³ç¨‹åº¦ï¼Œä¹Ÿç§°è‰²å½©çš„çº¯åº¦ã€‚é¥±å’Œåº¦å–å†³äºŽè¯¥è‰²ä¸­å«è‰²æˆåˆ†å’Œæ¶ˆè‰²æˆåˆ†ï¼ˆç°è‰²ï¼‰çš„æ¯”ä¾‹ã€‚å«è‰²æˆåˆ†è¶Šå¤§ï¼Œé¥±å’Œåº¦è¶Šå¤§ï¼›æ¶ˆè‰²æˆåˆ†è¶Šå¤§ï¼Œé¥±å’Œåº¦è¶Šå°ã€‚
	    äº®åº¦ brightness
	        http://baike.baidu.com/view/34773.htm
	        äº®åº¦æ˜¯æŒ‡å‘å…‰ä½“ï¼ˆåå…‰ä½“ï¼‰è¡¨é¢å‘å…‰ï¼ˆåå…‰ï¼‰å¼ºå¼±çš„ç‰©ç†é‡ã€‚
	    ç…§åº¦ luminosity
	        ç‰©ä½“è¢«ç…§äº®çš„ç¨‹åº¦,é‡‡ç”¨å•ä½é¢ç§¯æ‰€æŽ¥å—çš„å…‰é€šé‡æ¥è¡¨ç¤º,è¡¨ç¤ºå•ä½ä¸ºå‹’[å…‹æ–¯](Lux,lx) ,å³ 1m / m2 ã€‚

	    http://stackoverflow.com/questions/1484506/random-color-generator-in-javascript
	        var letters = '0123456789ABCDEF'.split('')
	        var color = '#'
	        for (var i = 0; i < 6; i++) {
	            color += letters[Math.floor(Math.random() * 16)]
	        }
	        return color
	    
	        // éšæœºç”Ÿæˆä¸€ä¸ªæ— è„‘çš„é¢œè‰²ï¼Œæ ¼å¼ä¸º '#RRGGBB'ã€‚
	        // _brainlessColor()
	        var color = Math.floor(
	            Math.random() *
	            (16 * 16 * 16 * 16 * 16 * 16 - 1)
	        ).toString(16)
	        color = "#" + ("000000" + color).slice(-6)
	        return color.toUpperCase()
	*/

	var Convert = __webpack_require__(11)
	var DICT = __webpack_require__(12)

	module.exports = {
	    // éšæœºç”Ÿæˆä¸€ä¸ªæœ‰å¸å¼•åŠ›çš„é¢œè‰²ï¼Œæ ¼å¼ä¸º '#RRGGBB'ã€‚
	    color: function(name) {
	        if (name || DICT[name]) return DICT[name].nicer
	        return this.hex()
	    },
	    // #DAC0DE
	    hex: function() {
	        var hsv = this._goldenRatioColor()
	        var rgb = Convert.hsv2rgb(hsv)
	        var hex = Convert.rgb2hex(rgb[0], rgb[1], rgb[2])
	        return hex
	    },
	    // rgb(128,255,255)
	    rgb: function() {
	        var hsv = this._goldenRatioColor()
	        var rgb = Convert.hsv2rgb(hsv)
	        return 'rgb(' +
	            parseInt(rgb[0], 10) + ', ' +
	            parseInt(rgb[1], 10) + ', ' +
	            parseInt(rgb[2], 10) + ')'
	    },
	    // rgba(128,255,255,0.3)
	    rgba: function() {
	        var hsv = this._goldenRatioColor()
	        var rgb = Convert.hsv2rgb(hsv)
	        return 'rgba(' +
	            parseInt(rgb[0], 10) + ', ' +
	            parseInt(rgb[1], 10) + ', ' +
	            parseInt(rgb[2], 10) + ', ' +
	            Math.random().toFixed(2) + ')'
	    },
	    // hsl(300,80%,90%)
	    hsl: function() {
	        var hsv = this._goldenRatioColor()
	        var hsl = Convert.hsv2hsl(hsv)
	        return 'hsl(' +
	            parseInt(hsl[0], 10) + ', ' +
	            parseInt(hsl[1], 10) + ', ' +
	            parseInt(hsl[2], 10) + ')'
	    },
	    // http://martin.ankerl.com/2009/12/09/how-to-create-random-colors-programmatically/
	    // https://github.com/devongovett/color-generator/blob/master/index.js
	    // éšæœºç”Ÿæˆä¸€ä¸ªæœ‰å¸å¼•åŠ›çš„é¢œè‰²ã€‚
	    _goldenRatioColor: function(saturation, value) {
	        this._goldenRatio = 0.618033988749895
	        this._hue = this._hue || Math.random()
	        this._hue += this._goldenRatio
	        this._hue %= 1

	        if (typeof saturation !== "number") saturation = 0.5;
	        if (typeof value !== "number") value = 0.95;

	        return [
	            this._hue * 360,
	            saturation * 100,
	            value * 100
	        ]
	    }
	}

/***/ },
/* 11 */
/***/ function(module, exports) {

	/*
	    ## Color Convert

	    http://blog.csdn.net/idfaya/article/details/6770414
	        é¢œè‰²ç©ºé—´RGBä¸ŽHSV(HSL)çš„è½¬æ¢
	*/
	// https://github.com/harthur/color-convert/blob/master/conversions.js
	module.exports = {
		rgb2hsl: function rgb2hsl(rgb) {
			var r = rgb[0] / 255,
				g = rgb[1] / 255,
				b = rgb[2] / 255,
				min = Math.min(r, g, b),
				max = Math.max(r, g, b),
				delta = max - min,
				h, s, l;

			if (max == min)
				h = 0;
			else if (r == max)
				h = (g - b) / delta;
			else if (g == max)
				h = 2 + (b - r) / delta;
			else if (b == max)
				h = 4 + (r - g) / delta;

			h = Math.min(h * 60, 360);

			if (h < 0)
				h += 360;

			l = (min + max) / 2;

			if (max == min)
				s = 0;
			else if (l <= 0.5)
				s = delta / (max + min);
			else
				s = delta / (2 - max - min);

			return [h, s * 100, l * 100];
		},
		rgb2hsv: function rgb2hsv(rgb) {
			var r = rgb[0],
				g = rgb[1],
				b = rgb[2],
				min = Math.min(r, g, b),
				max = Math.max(r, g, b),
				delta = max - min,
				h, s, v;

			if (max === 0)
				s = 0;
			else
				s = (delta / max * 1000) / 10;

			if (max == min)
				h = 0;
			else if (r == max)
				h = (g - b) / delta;
			else if (g == max)
				h = 2 + (b - r) / delta;
			else if (b == max)
				h = 4 + (r - g) / delta;

			h = Math.min(h * 60, 360);

			if (h < 0)
				h += 360;

			v = ((max / 255) * 1000) / 10;

			return [h, s, v];
		},
		hsl2rgb: function hsl2rgb(hsl) {
			var h = hsl[0] / 360,
				s = hsl[1] / 100,
				l = hsl[2] / 100,
				t1, t2, t3, rgb, val;

			if (s === 0) {
				val = l * 255;
				return [val, val, val];
			}

			if (l < 0.5)
				t2 = l * (1 + s);
			else
				t2 = l + s - l * s;
			t1 = 2 * l - t2;

			rgb = [0, 0, 0];
			for (var i = 0; i < 3; i++) {
				t3 = h + 1 / 3 * -(i - 1);
				if (t3 < 0) t3++;
				if (t3 > 1) t3--;

				if (6 * t3 < 1)
					val = t1 + (t2 - t1) * 6 * t3;
				else if (2 * t3 < 1)
					val = t2;
				else if (3 * t3 < 2)
					val = t1 + (t2 - t1) * (2 / 3 - t3) * 6;
				else
					val = t1;

				rgb[i] = val * 255;
			}

			return rgb;
		},
		hsl2hsv: function hsl2hsv(hsl) {
			var h = hsl[0],
				s = hsl[1] / 100,
				l = hsl[2] / 100,
				sv, v;
			l *= 2;
			s *= (l <= 1) ? l : 2 - l;
			v = (l + s) / 2;
			sv = (2 * s) / (l + s);
			return [h, sv * 100, v * 100];
		},
		hsv2rgb: function hsv2rgb(hsv) {
			var h = hsv[0] / 60
			var s = hsv[1] / 100
			var v = hsv[2] / 100
			var hi = Math.floor(h) % 6

			var f = h - Math.floor(h)
			var p = 255 * v * (1 - s)
			var q = 255 * v * (1 - (s * f))
			var t = 255 * v * (1 - (s * (1 - f)))

			v = 255 * v

			switch (hi) {
				case 0:
					return [v, t, p]
				case 1:
					return [q, v, p]
				case 2:
					return [p, v, t]
				case 3:
					return [p, q, v]
				case 4:
					return [t, p, v]
				case 5:
					return [v, p, q]
			}
		},
		hsv2hsl: function hsv2hsl(hsv) {
			var h = hsv[0],
				s = hsv[1] / 100,
				v = hsv[2] / 100,
				sl, l;

			l = (2 - s) * v;
			sl = s * v;
			sl /= (l <= 1) ? l : 2 - l;
			l /= 2;
			return [h, sl * 100, l * 100];
		},
		// http://www.140byt.es/keywords/color
		rgb2hex: function(
			a, // red, as a number from 0 to 255
			b, // green, as a number from 0 to 255
			c // blue, as a number from 0 to 255
		) {
			return "#" + ((256 + a << 8 | b) << 8 | c).toString(16).slice(1)
		},
		hex2rgb: function(
			a // take a "#xxxxxx" hex string,
		) {
			a = '0x' + a.slice(1).replace(a.length > 4 ? a : /./g, '$&$&') | 0;
			return [a >> 16, a >> 8 & 255, a & 255]
		}
	}

/***/ },
/* 12 */
/***/ function(module, exports) {

	/*
	    ## Color å­—å…¸æ•°æ®

	    å­—å…¸æ•°æ®æ¥æº [A nicer color palette for the web](http://clrs.cc/)
	*/
	module.exports = {
	    // name value nicer
	    navy: {
	        value: '#000080',
	        nicer: '#001F3F'
	    },
	    blue: {
	        value: '#0000ff',
	        nicer: '#0074D9'
	    },
	    aqua: {
	        value: '#00ffff',
	        nicer: '#7FDBFF'
	    },
	    teal: {
	        value: '#008080',
	        nicer: '#39CCCC'
	    },
	    olive: {
	        value: '#008000',
	        nicer: '#3D9970'
	    },
	    green: {
	        value: '#008000',
	        nicer: '#2ECC40'
	    },
	    lime: {
	        value: '#00ff00',
	        nicer: '#01FF70'
	    },
	    yellow: {
	        value: '#ffff00',
	        nicer: '#FFDC00'
	    },
	    orange: {
	        value: '#ffa500',
	        nicer: '#FF851B'
	    },
	    red: {
	        value: '#ff0000',
	        nicer: '#FF4136'
	    },
	    maroon: {
	        value: '#800000',
	        nicer: '#85144B'
	    },
	    fuchsia: {
	        value: '#ff00ff',
	        nicer: '#F012BE'
	    },
	    purple: {
	        value: '#800080',
	        nicer: '#B10DC9'
	    },
	    silver: {
	        value: '#c0c0c0',
	        nicer: '#DDDDDD'
	    },
	    gray: {
	        value: '#808080',
	        nicer: '#AAAAAA'
	    },
	    black: {
	        value: '#000000',
	        nicer: '#111111'
	    },
	    white: {
	        value: '#FFFFFF',
	        nicer: '#FFFFFF'
	    }
	}

/***/ },
/* 13 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Text

	    http://www.lipsum.com/
	*/
	var Basic = __webpack_require__(6)
	var Helper = __webpack_require__(14)

	function range(defaultMin, defaultMax, min, max) {
	    return min === undefined ? Basic.natural(defaultMin, defaultMax) : // ()
	        max === undefined ? min : // ( len )
	        Basic.natural(parseInt(min, 10), parseInt(max, 10)) // ( min, max )
	}

	module.exports = {
	    // éšæœºç”Ÿæˆä¸€æ®µæ–‡æœ¬ã€‚
	    paragraph: function(min, max) {
	        var len = range(3, 7, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.sentence())
	        }
	        return result.join(' ')
	    },
	    // 
	    cparagraph: function(min, max) {
	        var len = range(3, 7, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.csentence())
	        }
	        return result.join('')
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªå¥å­ï¼Œç¬¬ä¸€ä¸ªå•è¯çš„é¦–å­—æ¯å¤§å†™ã€‚
	    sentence: function(min, max) {
	        var len = range(12, 18, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.word())
	        }
	        return Helper.capitalize(result.join(' ')) + '.'
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªä¸­æ–‡å¥å­ã€‚
	    csentence: function(min, max) {
	        var len = range(12, 18, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.cword())
	        }

	        return result.join('') + 'ã€‚'
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªå•è¯ã€‚
	    word: function(min, max) {
	        var len = range(3, 10, min, max)
	        var result = '';
	        for (var i = 0; i < len; i++) {
	            result += Basic.character('lower')
	        }
	        return result
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªæˆ–å¤šä¸ªæ±‰å­—ã€‚
	    cword: function(pool, min, max) {
	        // æœ€å¸¸ç”¨çš„ 500 ä¸ªæ±‰å­— http://baike.baidu.com/view/568436.htm
	        var DICT_KANZI = 'çš„ä¸€æ˜¯åœ¨ä¸äº†æœ‰å’Œäººè¿™ä¸­å¤§ä¸ºä¸Šä¸ªå›½æˆ‘ä»¥è¦ä»–æ—¶æ¥ç”¨ä»¬ç”Ÿåˆ°ä½œåœ°äºŽå‡ºå°±åˆ†å¯¹æˆä¼šå¯ä¸»å‘å¹´åŠ¨åŒå·¥ä¹Ÿèƒ½ä¸‹è¿‡å­è¯´äº§ç§é¢è€Œæ–¹åŽå¤šå®šè¡Œå­¦æ³•æ‰€æ°‘å¾—ç»åä¸‰ä¹‹è¿›ç€ç­‰éƒ¨åº¦å®¶ç”µåŠ›é‡Œå¦‚æ°´åŒ–é«˜è‡ªäºŒç†èµ·å°ç‰©çŽ°å®žåŠ é‡éƒ½ä¸¤ä½“åˆ¶æœºå½“ä½¿ç‚¹ä»Žä¸šæœ¬åŽ»æŠŠæ€§å¥½åº”å¼€å®ƒåˆè¿˜å› ç”±å…¶äº›ç„¶å‰å¤–å¤©æ”¿å››æ—¥é‚£ç¤¾ä¹‰äº‹å¹³å½¢ç›¸å…¨è¡¨é—´æ ·ä¸Žå…³å„é‡æ–°çº¿å†…æ•°æ­£å¿ƒåä½ æ˜Žçœ‹åŽŸåˆä¹ˆåˆ©æ¯”æˆ–ä½†è´¨æ°”ç¬¬å‘é“å‘½æ­¤å˜æ¡åªæ²¡ç»“è§£é—®æ„å»ºæœˆå…¬æ— ç³»å†›å¾ˆæƒ…è€…æœ€ç«‹ä»£æƒ³å·²é€šå¹¶æç›´é¢˜å…šç¨‹å±•äº”æžœæ–™è±¡å‘˜é©ä½å…¥å¸¸æ–‡æ€»æ¬¡å“å¼æ´»è®¾åŠç®¡ç‰¹ä»¶é•¿æ±‚è€å¤´åŸºèµ„è¾¹æµè·¯çº§å°‘å›¾å±±ç»ŸæŽ¥çŸ¥è¾ƒå°†ç»„è§è®¡åˆ«å¥¹æ‰‹è§’æœŸæ ¹è®ºè¿å†œæŒ‡å‡ ä¹åŒºå¼ºæ”¾å†³è¥¿è¢«å¹²åšå¿…æˆ˜å…ˆå›žåˆ™ä»»å–æ®å¤„é˜Ÿå—ç»™è‰²å…‰é—¨å³ä¿æ²»åŒ—é€ ç™¾è§„çƒ­é¢†ä¸ƒæµ·å£ä¸œå¯¼å™¨åŽ‹å¿—ä¸–é‡‘å¢žäº‰æµŽé˜¶æ²¹æ€æœ¯æžäº¤å—è”ä»€è®¤å…­å…±æƒæ”¶è¯æ”¹æ¸…å·±ç¾Žå†é‡‡è½¬æ›´å•é£Žåˆ‡æ‰“ç™½æ•™é€ŸèŠ±å¸¦å®‰åœºèº«è½¦ä¾‹çœŸåŠ¡å…·ä¸‡æ¯ç›®è‡³è¾¾èµ°ç§¯ç¤ºè®®å£°æŠ¥æ–—å®Œç±»å…«ç¦»åŽåç¡®æ‰ç§‘å¼ ä¿¡é©¬èŠ‚è¯ç±³æ•´ç©ºå…ƒå†µä»Šé›†æ¸©ä¼ åœŸè®¸æ­¥ç¾¤å¹¿çŸ³è®°éœ€æ®µç ”ç•Œæ‹‰æž—å¾‹å«ä¸”ç©¶è§‚è¶Šç»‡è£…å½±ç®—ä½ŽæŒéŸ³ä¼—ä¹¦å¸ƒå¤å®¹å„¿é¡»é™…å•†éžéªŒè¿žæ–­æ·±éš¾è¿‘çŸ¿åƒå‘¨å§”ç´ æŠ€å¤‡åŠåŠžé’çœåˆ—ä¹ å“çº¦æ”¯èˆ¬å²æ„ŸåŠ³ä¾¿å›¢å¾€é…¸åŽ†å¸‚å…‹ä½•é™¤æ¶ˆæž„åºœç§°å¤ªå‡†ç²¾å€¼å·çŽ‡æ—ç»´åˆ’é€‰æ ‡å†™å­˜å€™æ¯›äº²å¿«æ•ˆæ–¯é™¢æŸ¥æ±Ÿåž‹çœ¼çŽ‹æŒ‰æ ¼å…»æ˜“ç½®æ´¾å±‚ç‰‡å§‹å´ä¸“çŠ¶è‚²åŽ‚äº¬è¯†é€‚å±žåœ†åŒ…ç«ä½è°ƒæ»¡åŽ¿å±€ç…§å‚çº¢ç»†å¼•å¬è¯¥é“ä»·ä¸¥é¾™é£ž'

	        var len
	        switch (arguments.length) {
	            case 0: // ()
	                pool = DICT_KANZI
	                len = 1
	                break
	            case 1: // ( pool )
	                if (typeof arguments[0] === 'string') {
	                    len = 1
	                } else {
	                    // ( length )
	                    len = pool
	                    pool = DICT_KANZI
	                }
	                break
	            case 2:
	                // ( pool, length )
	                if (typeof arguments[0] === 'string') {
	                    len = min
	                } else {
	                    // ( min, max )
	                    len = this.natural(pool, min)
	                    pool = DICT_KANZI
	                }
	                break
	            case 3:
	                len = this.natural(min, max)
	                break
	        }

	        var result = ''
	        for (var i = 0; i < len; i++) {
	            result += pool.charAt(this.natural(0, pool.length - 1))
	        }
	        return result
	    },
	    // éšæœºç”Ÿæˆä¸€å¥æ ‡é¢˜ï¼Œå…¶ä¸­æ¯ä¸ªå•è¯çš„é¦–å­—æ¯å¤§å†™ã€‚
	    title: function(min, max) {
	        var len = range(3, 7, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.capitalize(this.word()))
	        }
	        return result.join(' ')
	    },
	    // éšæœºç”Ÿæˆä¸€å¥ä¸­æ–‡æ ‡é¢˜ã€‚
	    ctitle: function(min, max) {
	        var len = range(3, 7, min, max)
	        var result = []
	        for (var i = 0; i < len; i++) {
	            result.push(this.cword())
	        }
	        return result.join('')
	    }
	}

/***/ },
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Helpers
	*/

	var Util = __webpack_require__(3)

	module.exports = {
		// æŠŠå­—ç¬¦ä¸²çš„ç¬¬ä¸€ä¸ªå­—æ¯è½¬æ¢ä¸ºå¤§å†™ã€‚
		capitalize: function(word) {
			return (word + '').charAt(0).toUpperCase() + (word + '').substr(1)
		},
		// æŠŠå­—ç¬¦ä¸²è½¬æ¢ä¸ºå¤§å†™ã€‚
		upper: function(str) {
			return (str + '').toUpperCase()
		},
		// æŠŠå­—ç¬¦ä¸²è½¬æ¢ä¸ºå°å†™ã€‚
		lower: function(str) {
			return (str + '').toLowerCase()
		},
		// ä»Žæ•°ç»„ä¸­éšæœºé€‰å–ä¸€ä¸ªå…ƒç´ ï¼Œå¹¶è¿”å›žã€‚
		pick: function pick(arr, min, max) {
			// pick( item1, item2 ... )
			if (!Util.isArray(arr)) {
				arr = [].slice.call(arguments)
				min = 1
				max = 1
			} else {
				// pick( [ item1, item2 ... ] )
				if (min === undefined) min = 1

				// pick( [ item1, item2 ... ], count )
				if (max === undefined) max = min
			}

			if (min === 1 && max === 1) return arr[this.natural(0, arr.length - 1)]

			// pick( [ item1, item2 ... ], min, max )
			return this.shuffle(arr, min, max)

			// é€šè¿‡å‚æ•°ä¸ªæ•°åˆ¤æ–­æ–¹æ³•ç­¾åï¼Œæ‰©å±•æ€§å¤ªå·®ï¼#90
			// switch (arguments.length) {
			// 	case 1:
			// 		// pick( [ item1, item2 ... ] )
			// 		return arr[this.natural(0, arr.length - 1)]
			// 	case 2:
			// 		// pick( [ item1, item2 ... ], count )
			// 		max = min
			// 			/* falls through */
			// 	case 3:
			// 		// pick( [ item1, item2 ... ], min, max )
			// 		return this.shuffle(arr, min, max)
			// }
		},
		/*
		    æ‰“ä¹±æ•°ç»„ä¸­å…ƒç´ çš„é¡ºåºï¼Œå¹¶è¿”å›žã€‚
		    Given an array, scramble the order and return it.

		    å…¶ä»–çš„å®žçŽ°æ€è·¯ï¼š
		        // https://code.google.com/p/jslibs/wiki/JavascriptTips
		        result = result.sort(function() {
		            return Math.random() - 0.5
		        })
		*/
		shuffle: function shuffle(arr, min, max) {
			arr = arr || []
			var old = arr.slice(0),
				result = [],
				index = 0,
				length = old.length;
			for (var i = 0; i < length; i++) {
				index = this.natural(0, old.length - 1)
				result.push(old[index])
				old.splice(index, 1)
			}
			switch (arguments.length) {
				case 0:
				case 1:
					return result
				case 2:
					max = min
						/* falls through */
				case 3:
					min = parseInt(min, 10)
					max = parseInt(max, 10)
					return result.slice(0, this.natural(min, max))
			}
		},
		/*
		    * Random.order(item, item)
		    * Random.order([item, item ...])

		    é¡ºåºèŽ·å–æ•°ç»„ä¸­çš„å…ƒç´ 

		    [JSONå¯¼å…¥æ•°ç»„æ”¯æŒæ•°ç»„æ•°æ®å½•å…¥](https://github.com/thx/RAP/issues/22)

		    ä¸æ”¯æŒå•ç‹¬è°ƒç”¨ï¼
		*/
		order: function order(array) {
			order.cache = order.cache || {}

			if (arguments.length > 1) array = [].slice.call(arguments, 0)

			// options.context.path/templatePath
			var options = order.options
			var templatePath = options.context.templatePath.join('.')

			var cache = (
				order.cache[templatePath] = order.cache[templatePath] || {
					index: 0,
					array: array
				}
			)

			return cache.array[cache.index++ % cache.array.length]
		}
	}

/***/ },
/* 15 */
/***/ function(module, exports) {

	/*
	    ## Name

	    [Beyond the Top 1000 Names](http://www.ssa.gov/oact/babynames/limits.html)
	*/
	module.exports = {
		// éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„è‹±æ–‡åã€‚
		first: function() {
			var names = [
				// male
				"James", "John", "Robert", "Michael", "William",
				"David", "Richard", "Charles", "Joseph", "Thomas",
				"Christopher", "Daniel", "Paul", "Mark", "Donald",
				"George", "Kenneth", "Steven", "Edward", "Brian",
				"Ronald", "Anthony", "Kevin", "Jason", "Matthew",
				"Gary", "Timothy", "Jose", "Larry", "Jeffrey",
				"Frank", "Scott", "Eric"
			].concat([
				// female
				"Mary", "Patricia", "Linda", "Barbara", "Elizabeth",
				"Jennifer", "Maria", "Susan", "Margaret", "Dorothy",
				"Lisa", "Nancy", "Karen", "Betty", "Helen",
				"Sandra", "Donna", "Carol", "Ruth", "Sharon",
				"Michelle", "Laura", "Sarah", "Kimberly", "Deborah",
				"Jessica", "Shirley", "Cynthia", "Angela", "Melissa",
				"Brenda", "Amy", "Anna"
			])
			return this.pick(names)
				// or this.capitalize(this.word())
		},
		// éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„è‹±æ–‡å§“ã€‚
		last: function() {
			var names = [
				"Smith", "Johnson", "Williams", "Brown", "Jones",
				"Miller", "Davis", "Garcia", "Rodriguez", "Wilson",
				"Martinez", "Anderson", "Taylor", "Thomas", "Hernandez",
				"Moore", "Martin", "Jackson", "Thompson", "White",
				"Lopez", "Lee", "Gonzalez", "Harris", "Clark",
				"Lewis", "Robinson", "Walker", "Perez", "Hall",
				"Young", "Allen"
			]
			return this.pick(names)
				// or this.capitalize(this.word())
		},
		// éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„è‹±æ–‡å§“åã€‚
		name: function(middle) {
			return this.first() + ' ' +
				(middle ? this.first() + ' ' : '') +
				this.last()
		},
		/*
		    éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„ä¸­æ–‡å§“ã€‚
		    [ä¸–ç•Œå¸¸ç”¨å§“æ°æŽ’è¡Œ](http://baike.baidu.com/view/1719115.htm)
		    [çŽ„æ´¾ç½‘ - ç½‘ç»œå°è¯´åˆ›ä½œè¾…åŠ©å¹³å°](http://xuanpai.sinaapp.com/)
		 */
		cfirst: function() {
			var names = (
				'çŽ‹ æŽ å¼  åˆ˜ é™ˆ æ¨ èµµ é»„ å‘¨ å´ ' +
				'å¾ å­™ èƒ¡ æœ± é«˜ æž— ä½• éƒ­ é©¬ ç½— ' +
				'æ¢ å®‹ éƒ‘ è°¢ éŸ© å” å†¯ äºŽ è‘£ è§ ' +
				'ç¨‹ æ›¹ è¢ é‚“ è®¸ å‚… æ²ˆ æ›¾ å½­ å• ' +
				'è‹ å¢ è’‹ è”¡ è´¾ ä¸ é­ è–› å¶ é˜Ž ' +
				'ä½™ æ½˜ æœ æˆ´ å¤ é”º æ±ª ç”° ä»» å§œ ' +
				'èŒƒ æ–¹ çŸ³ å§š è°­ å»– é‚¹ ç†Š é‡‘ é™† ' +
				'éƒ å­” ç™½ å´” åº· æ¯› é‚± ç§¦ æ±Ÿ å² ' +
				'é¡¾ ä¾¯ é‚µ å­Ÿ é¾™ ä¸‡ æ®µ é›· é’± æ±¤ ' +
				'å°¹ é»Ž æ˜“ å¸¸ æ­¦ ä¹” è´º èµ– é¾š æ–‡'
			).split(' ')
			return this.pick(names)
		},
		/*
		    éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„ä¸­æ–‡åã€‚
		    [ä¸­å›½æœ€å¸¸è§åå­—å‰50å_ä¸‰ä¹ç®—å‘½ç½‘](http://www.name999.net/xingming/xingshi/20131004/48.html)
		 */
		clast: function() {
			var names = (
				'ä¼Ÿ èŠ³ å¨œ ç§€è‹± æ• é™ ä¸½ å¼º ç£Š å†› ' +
				'æ´‹ å‹‡ è‰³ æ° å¨Ÿ æ¶› æ˜Ž è¶… ç§€å…° éœž ' +
				'å¹³ åˆš æ¡‚è‹±'
			).split(' ')
			return this.pick(names)
		},
		// éšæœºç”Ÿæˆä¸€ä¸ªå¸¸è§çš„ä¸­æ–‡å§“åã€‚
		cname: function() {
			return this.cfirst() + this.clast()
		}
	}

/***/ },
/* 16 */
/***/ function(module, exports) {

	/*
	    ## Web
	*/
	module.exports = {
	    /*
	        éšæœºç”Ÿæˆä¸€ä¸ª URLã€‚

	        [URL è§„èŒƒ](http://www.w3.org/Addressing/URL/url-spec.txt)
	            http                    Hypertext Transfer Protocol 
	            ftp                     File Transfer protocol 
	            gopher                  The Gopher protocol 
	            mailto                  Electronic mail address 
	            mid                     Message identifiers for electronic mail 
	            cid                     Content identifiers for MIME body part 
	            news                    Usenet news 
	            nntp                    Usenet news for local NNTP access only 
	            prospero                Access using the prospero protocols 
	            telnet rlogin tn3270    Reference to interactive sessions
	            wais                    Wide Area Information Servers 
	    */
	    url: function(protocol, host) {
	        return (protocol || this.protocol()) + '://' + // protocol?
	            (host || this.domain()) + // host?
	            '/' + this.word()
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ª URL åè®®ã€‚
	    protocol: function() {
	        return this.pick(
	            // åè®®ç°‡
	            'http ftp gopher mailto mid cid news nntp prospero telnet rlogin tn3270 wais'.split(' ')
	        )
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªåŸŸåã€‚
	    domain: function(tld) {
	        return this.word() + '.' + (tld || this.tld())
	    },
	    /*
	        éšæœºç”Ÿæˆä¸€ä¸ªé¡¶çº§åŸŸåã€‚
	        å›½é™…é¡¶çº§åŸŸå international top-level domain-names, iTLDs
	        å›½å®¶é¡¶çº§åŸŸå national top-level domainnames, nTLDs
	        [åŸŸååŽç¼€å¤§å…¨](http://www.163ns.com/zixun/post/4417.html)
	    */
	    tld: function() { // Top Level Domain
	        return this.pick(
	            (
	                // åŸŸååŽç¼€
	                'com net org edu gov int mil cn ' +
	                // å›½å†…åŸŸå
	                'com.cn net.cn gov.cn org.cn ' +
	                // ä¸­æ–‡å›½å†…åŸŸå
	                'ä¸­å›½ ä¸­å›½äº’è”.å…¬å¸ ä¸­å›½äº’è”.ç½‘ç»œ ' +
	                // æ–°å›½é™…åŸŸå
	                'tel biz cc tv info name hk mobi asia cd travel pro museum coop aero ' +
	                // ä¸–ç•Œå„å›½åŸŸååŽç¼€
	                'ad ae af ag ai al am an ao aq ar as at au aw az ba bb bd be bf bg bh bi bj bm bn bo br bs bt bv bw by bz ca cc cf cg ch ci ck cl cm cn co cq cr cu cv cx cy cz de dj dk dm do dz ec ee eg eh es et ev fi fj fk fm fo fr ga gb gd ge gf gh gi gl gm gn gp gr gt gu gw gy hk hm hn hr ht hu id ie il in io iq ir is it jm jo jp ke kg kh ki km kn kp kr kw ky kz la lb lc li lk lr ls lt lu lv ly ma mc md mg mh ml mm mn mo mp mq mr ms mt mv mw mx my mz na nc ne nf ng ni nl no np nr nt nu nz om qa pa pe pf pg ph pk pl pm pn pr pt pw py re ro ru rw sa sb sc sd se sg sh si sj sk sl sm sn so sr st su sy sz tc td tf tg th tj tk tm tn to tp tr tt tv tw tz ua ug uk us uy va vc ve vg vn vu wf ws ye yu za zm zr zw'
	            ).split(' ')
	        )
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªé‚®ä»¶åœ°å€ã€‚
	    email: function(domain) {
	        return this.character('lower') + '.' + this.word() + '@' +
	            (
	                domain ||
	                (this.word() + '.' + this.tld())
	            )
	            // return this.character('lower') + '.' + this.last().toLowerCase() + '@' + this.last().toLowerCase() + '.' + this.tld()
	            // return this.word() + '@' + (domain || this.domain())
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ª IP åœ°å€ã€‚
	    ip: function() {
	        return this.natural(0, 255) + '.' +
	            this.natural(0, 255) + '.' +
	            this.natural(0, 255) + '.' +
	            this.natural(0, 255)
	    }
	}

/***/ },
/* 17 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Address
	*/

	var DICT = __webpack_require__(18)
	var REGION = ['ä¸œåŒ—', 'åŽåŒ—', 'åŽä¸œ', 'åŽä¸­', 'åŽå—', 'è¥¿å—', 'è¥¿åŒ—']

	module.exports = {
	    // éšæœºç”Ÿæˆä¸€ä¸ªå¤§åŒºã€‚
	    region: function() {
	        return this.pick(REGION)
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªï¼ˆä¸­å›½ï¼‰çœï¼ˆæˆ–ç›´è¾–å¸‚ã€è‡ªæ²»åŒºã€ç‰¹åˆ«è¡Œæ”¿åŒºï¼‰ã€‚
	    province: function() {
	        return this.pick(DICT).name
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªï¼ˆä¸­å›½ï¼‰å¸‚ã€‚
	    city: function(prefix) {
	        var province = this.pick(DICT)
	        var city = this.pick(province.children)
	        return prefix ? [province.name, city.name].join(' ') : city.name
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªï¼ˆä¸­å›½ï¼‰åŽ¿ã€‚
	    county: function(prefix) {
	        var province = this.pick(DICT)
	        var city = this.pick(province.children)
	        var county = this.pick(city.children) || {
	            name: '-'
	        }
	        return prefix ? [province.name, city.name, county.name].join(' ') : county.name
	    },
	    // éšæœºç”Ÿæˆä¸€ä¸ªé‚®æ”¿ç¼–ç ï¼ˆå…­ä½æ•°å­—ï¼‰ã€‚
	    zip: function(len) {
	        var zip = ''
	        for (var i = 0; i < (len || 6); i++) zip += this.natural(0, 9)
	        return zip
	    }

	    // address: function() {},
	    // phone: function() {},
	    // areacode: function() {},
	    // street: function() {},
	    // street_suffixes: function() {},
	    // street_suffix: function() {},
	    // states: function() {},
	    // state: function() {},
	}

/***/ },
/* 18 */
/***/ function(module, exports) {

	/*
	    ## Address å­—å…¸æ•°æ®

	    å­—å…¸æ•°æ®æ¥æº http://www.atatech.org/articles/30028?rnd=254259856

	    å›½æ ‡ çœï¼ˆå¸‚ï¼‰çº§è¡Œæ”¿åŒºåˆ’ç è¡¨

	    åŽåŒ—   åŒ—äº¬å¸‚ å¤©æ´¥å¸‚ æ²³åŒ—çœ å±±è¥¿çœ å†…è’™å¤è‡ªæ²»åŒº
	    ä¸œåŒ—   è¾½å®çœ å‰æž—çœ é»‘é¾™æ±Ÿçœ
	    åŽä¸œ   ä¸Šæµ·å¸‚ æ±Ÿè‹çœ æµ™æ±Ÿçœ å®‰å¾½çœ ç¦å»ºçœ æ±Ÿè¥¿çœ å±±ä¸œçœ
	    åŽå—   å¹¿ä¸œçœ å¹¿è¥¿å£®æ—è‡ªæ²»åŒº æµ·å—çœ
	    åŽä¸­   æ²³å—çœ æ¹–åŒ—çœ æ¹–å—çœ
	    è¥¿å—   é‡åº†å¸‚ å››å·çœ è´µå·žçœ äº‘å—çœ è¥¿è—è‡ªæ²»åŒº
	    è¥¿åŒ—   é™•è¥¿çœ ç”˜è‚ƒçœ é’æµ·çœ å®å¤å›žæ—è‡ªæ²»åŒº æ–°ç–†ç»´å¾å°”è‡ªæ²»åŒº
	    æ¸¯æ¾³å° é¦™æ¸¯ç‰¹åˆ«è¡Œæ”¿åŒº æ¾³é—¨ç‰¹åˆ«è¡Œæ”¿åŒº å°æ¹¾çœ
	    
	    **æŽ’åº**
	    
	    ```js
	    var map = {}
	    _.each(_.keys(REGIONS),function(id){
	      map[id] = REGIONS[ID]
	    })
	    JSON.stringify(map)
	    ```
	*/
	var DICT = {
	    "110000": "åŒ—äº¬",
	    "110100": "åŒ—äº¬å¸‚",
	    "110101": "ä¸œåŸŽåŒº",
	    "110102": "è¥¿åŸŽåŒº",
	    "110105": "æœé˜³åŒº",
	    "110106": "ä¸°å°åŒº",
	    "110107": "çŸ³æ™¯å±±åŒº",
	    "110108": "æµ·æ·€åŒº",
	    "110109": "é—¨å¤´æ²ŸåŒº",
	    "110111": "æˆ¿å±±åŒº",
	    "110112": "é€šå·žåŒº",
	    "110113": "é¡ºä¹‰åŒº",
	    "110114": "æ˜Œå¹³åŒº",
	    "110115": "å¤§å…´åŒº",
	    "110116": "æ€€æŸ”åŒº",
	    "110117": "å¹³è°·åŒº",
	    "110228": "å¯†äº‘åŽ¿",
	    "110229": "å»¶åº†åŽ¿",
	    "110230": "å…¶å®ƒåŒº",
	    "120000": "å¤©æ´¥",
	    "120100": "å¤©æ´¥å¸‚",
	    "120101": "å’Œå¹³åŒº",
	    "120102": "æ²³ä¸œåŒº",
	    "120103": "æ²³è¥¿åŒº",
	    "120104": "å—å¼€åŒº",
	    "120105": "æ²³åŒ—åŒº",
	    "120106": "çº¢æ¡¥åŒº",
	    "120110": "ä¸œä¸½åŒº",
	    "120111": "è¥¿é’åŒº",
	    "120112": "æ´¥å—åŒº",
	    "120113": "åŒ—è¾°åŒº",
	    "120114": "æ­¦æ¸…åŒº",
	    "120115": "å®å»åŒº",
	    "120116": "æ»¨æµ·æ–°åŒº",
	    "120221": "å®æ²³åŽ¿",
	    "120223": "é™æµ·åŽ¿",
	    "120225": "è“ŸåŽ¿",
	    "120226": "å…¶å®ƒåŒº",
	    "130000": "æ²³åŒ—çœ",
	    "130100": "çŸ³å®¶åº„å¸‚",
	    "130102": "é•¿å®‰åŒº",
	    "130103": "æ¡¥ä¸œåŒº",
	    "130104": "æ¡¥è¥¿åŒº",
	    "130105": "æ–°åŽåŒº",
	    "130107": "äº•é™‰çŸ¿åŒº",
	    "130108": "è£•åŽåŒº",
	    "130121": "äº•é™‰åŽ¿",
	    "130123": "æ­£å®šåŽ¿",
	    "130124": "æ ¾åŸŽåŽ¿",
	    "130125": "è¡Œå”åŽ¿",
	    "130126": "çµå¯¿åŽ¿",
	    "130127": "é«˜é‚‘åŽ¿",
	    "130128": "æ·±æ³½åŽ¿",
	    "130129": "èµžçš‡åŽ¿",
	    "130130": "æ— æžåŽ¿",
	    "130131": "å¹³å±±åŽ¿",
	    "130132": "å…ƒæ°åŽ¿",
	    "130133": "èµµåŽ¿",
	    "130181": "è¾›é›†å¸‚",
	    "130182": "è—åŸŽå¸‚",
	    "130183": "æ™‹å·žå¸‚",
	    "130184": "æ–°ä¹å¸‚",
	    "130185": "é¹¿æ³‰å¸‚",
	    "130186": "å…¶å®ƒåŒº",
	    "130200": "å”å±±å¸‚",
	    "130202": "è·¯å—åŒº",
	    "130203": "è·¯åŒ—åŒº",
	    "130204": "å¤å†¶åŒº",
	    "130205": "å¼€å¹³åŒº",
	    "130207": "ä¸°å—åŒº",
	    "130208": "ä¸°æ¶¦åŒº",
	    "130223": "æ»¦åŽ¿",
	    "130224": "æ»¦å—åŽ¿",
	    "130225": "ä¹äº­åŽ¿",
	    "130227": "è¿è¥¿åŽ¿",
	    "130229": "çŽ‰ç”°åŽ¿",
	    "130230": "æ›¹å¦ƒç”¸åŒº",
	    "130281": "éµåŒ–å¸‚",
	    "130283": "è¿å®‰å¸‚",
	    "130284": "å…¶å®ƒåŒº",
	    "130300": "ç§¦çš‡å²›å¸‚",
	    "130302": "æµ·æ¸¯åŒº",
	    "130303": "å±±æµ·å…³åŒº",
	    "130304": "åŒ—æˆ´æ²³åŒº",
	    "130321": "é’é¾™æ»¡æ—è‡ªæ²»åŽ¿",
	    "130322": "æ˜Œé»ŽåŽ¿",
	    "130323": "æŠšå®åŽ¿",
	    "130324": "å¢é¾™åŽ¿",
	    "130398": "å…¶å®ƒåŒº",
	    "130400": "é‚¯éƒ¸å¸‚",
	    "130402": "é‚¯å±±åŒº",
	    "130403": "ä¸›å°åŒº",
	    "130404": "å¤å…´åŒº",
	    "130406": "å³°å³°çŸ¿åŒº",
	    "130421": "é‚¯éƒ¸åŽ¿",
	    "130423": "ä¸´æ¼³åŽ¿",
	    "130424": "æˆå®‰åŽ¿",
	    "130425": "å¤§ååŽ¿",
	    "130426": "æ¶‰åŽ¿",
	    "130427": "ç£åŽ¿",
	    "130428": "è‚¥ä¹¡åŽ¿",
	    "130429": "æ°¸å¹´åŽ¿",
	    "130430": "é‚±åŽ¿",
	    "130431": "é¸¡æ³½åŽ¿",
	    "130432": "å¹¿å¹³åŽ¿",
	    "130433": "é¦†é™¶åŽ¿",
	    "130434": "é­åŽ¿",
	    "130435": "æ›²å‘¨åŽ¿",
	    "130481": "æ­¦å®‰å¸‚",
	    "130482": "å…¶å®ƒåŒº",
	    "130500": "é‚¢å°å¸‚",
	    "130502": "æ¡¥ä¸œåŒº",
	    "130503": "æ¡¥è¥¿åŒº",
	    "130521": "é‚¢å°åŽ¿",
	    "130522": "ä¸´åŸŽåŽ¿",
	    "130523": "å†…ä¸˜åŽ¿",
	    "130524": "æŸä¹¡åŽ¿",
	    "130525": "éš†å°§åŽ¿",
	    "130526": "ä»»åŽ¿",
	    "130527": "å—å’ŒåŽ¿",
	    "130528": "å®æ™‹åŽ¿",
	    "130529": "å·¨é¹¿åŽ¿",
	    "130530": "æ–°æ²³åŽ¿",
	    "130531": "å¹¿å®—åŽ¿",
	    "130532": "å¹³ä¹¡åŽ¿",
	    "130533": "å¨åŽ¿",
	    "130534": "æ¸…æ²³åŽ¿",
	    "130535": "ä¸´è¥¿åŽ¿",
	    "130581": "å—å®«å¸‚",
	    "130582": "æ²™æ²³å¸‚",
	    "130583": "å…¶å®ƒåŒº",
	    "130600": "ä¿å®šå¸‚",
	    "130602": "æ–°å¸‚åŒº",
	    "130603": "åŒ—å¸‚åŒº",
	    "130604": "å—å¸‚åŒº",
	    "130621": "æ»¡åŸŽåŽ¿",
	    "130622": "æ¸…è‹‘åŽ¿",
	    "130623": "æ¶žæ°´åŽ¿",
	    "130624": "é˜œå¹³åŽ¿",
	    "130625": "å¾æ°´åŽ¿",
	    "130626": "å®šå…´åŽ¿",
	    "130627": "å”åŽ¿",
	    "130628": "é«˜é˜³åŽ¿",
	    "130629": "å®¹åŸŽåŽ¿",
	    "130630": "æ¶žæºåŽ¿",
	    "130631": "æœ›éƒ½åŽ¿",
	    "130632": "å®‰æ–°åŽ¿",
	    "130633": "æ˜“åŽ¿",
	    "130634": "æ›²é˜³åŽ¿",
	    "130635": "è ¡åŽ¿",
	    "130636": "é¡ºå¹³åŽ¿",
	    "130637": "åšé‡ŽåŽ¿",
	    "130638": "é›„åŽ¿",
	    "130681": "æ¶¿å·žå¸‚",
	    "130682": "å®šå·žå¸‚",
	    "130683": "å®‰å›½å¸‚",
	    "130684": "é«˜ç¢‘åº—å¸‚",
	    "130699": "å…¶å®ƒåŒº",
	    "130700": "å¼ å®¶å£å¸‚",
	    "130702": "æ¡¥ä¸œåŒº",
	    "130703": "æ¡¥è¥¿åŒº",
	    "130705": "å®£åŒ–åŒº",
	    "130706": "ä¸‹èŠ±å›­åŒº",
	    "130721": "å®£åŒ–åŽ¿",
	    "130722": "å¼ åŒ—åŽ¿",
	    "130723": "åº·ä¿åŽ¿",
	    "130724": "æ²½æºåŽ¿",
	    "130725": "å°šä¹‰åŽ¿",
	    "130726": "è”šåŽ¿",
	    "130727": "é˜³åŽŸåŽ¿",
	    "130728": "æ€€å®‰åŽ¿",
	    "130729": "ä¸‡å…¨åŽ¿",
	    "130730": "æ€€æ¥åŽ¿",
	    "130731": "æ¶¿é¹¿åŽ¿",
	    "130732": "èµ¤åŸŽåŽ¿",
	    "130733": "å´‡ç¤¼åŽ¿",
	    "130734": "å…¶å®ƒåŒº",
	    "130800": "æ‰¿å¾·å¸‚",
	    "130802": "åŒæ¡¥åŒº",
	    "130803": "åŒæ»¦åŒº",
	    "130804": "é¹°æ‰‹è¥å­çŸ¿åŒº",
	    "130821": "æ‰¿å¾·åŽ¿",
	    "130822": "å…´éš†åŽ¿",
	    "130823": "å¹³æ³‰åŽ¿",
	    "130824": "æ»¦å¹³åŽ¿",
	    "130825": "éš†åŒ–åŽ¿",
	    "130826": "ä¸°å®æ»¡æ—è‡ªæ²»åŽ¿",
	    "130827": "å®½åŸŽæ»¡æ—è‡ªæ²»åŽ¿",
	    "130828": "å›´åœºæ»¡æ—è’™å¤æ—è‡ªæ²»åŽ¿",
	    "130829": "å…¶å®ƒåŒº",
	    "130900": "æ²§å·žå¸‚",
	    "130902": "æ–°åŽåŒº",
	    "130903": "è¿æ²³åŒº",
	    "130921": "æ²§åŽ¿",
	    "130922": "é’åŽ¿",
	    "130923": "ä¸œå…‰åŽ¿",
	    "130924": "æµ·å…´åŽ¿",
	    "130925": "ç›å±±åŽ¿",
	    "130926": "è‚ƒå®åŽ¿",
	    "130927": "å—çš®åŽ¿",
	    "130928": "å´æ¡¥åŽ¿",
	    "130929": "çŒ®åŽ¿",
	    "130930": "å­Ÿæ‘å›žæ—è‡ªæ²»åŽ¿",
	    "130981": "æ³Šå¤´å¸‚",
	    "130982": "ä»»ä¸˜å¸‚",
	    "130983": "é»„éª…å¸‚",
	    "130984": "æ²³é—´å¸‚",
	    "130985": "å…¶å®ƒåŒº",
	    "131000": "å»ŠåŠå¸‚",
	    "131002": "å®‰æ¬¡åŒº",
	    "131003": "å¹¿é˜³åŒº",
	    "131022": "å›ºå®‰åŽ¿",
	    "131023": "æ°¸æ¸…åŽ¿",
	    "131024": "é¦™æ²³åŽ¿",
	    "131025": "å¤§åŸŽåŽ¿",
	    "131026": "æ–‡å®‰åŽ¿",
	    "131028": "å¤§åŽ‚å›žæ—è‡ªæ²»åŽ¿",
	    "131081": "éœ¸å·žå¸‚",
	    "131082": "ä¸‰æ²³å¸‚",
	    "131083": "å…¶å®ƒåŒº",
	    "131100": "è¡¡æ°´å¸‚",
	    "131102": "æ¡ƒåŸŽåŒº",
	    "131121": "æž£å¼ºåŽ¿",
	    "131122": "æ­¦é‚‘åŽ¿",
	    "131123": "æ­¦å¼ºåŽ¿",
	    "131124": "é¥¶é˜³åŽ¿",
	    "131125": "å®‰å¹³åŽ¿",
	    "131126": "æ•…åŸŽåŽ¿",
	    "131127": "æ™¯åŽ¿",
	    "131128": "é˜œåŸŽåŽ¿",
	    "131181": "å†€å·žå¸‚",
	    "131182": "æ·±å·žå¸‚",
	    "131183": "å…¶å®ƒåŒº",
	    "140000": "å±±è¥¿çœ",
	    "140100": "å¤ªåŽŸå¸‚",
	    "140105": "å°åº—åŒº",
	    "140106": "è¿Žæ³½åŒº",
	    "140107": "æèŠ±å²­åŒº",
	    "140108": "å°–è‰åªåŒº",
	    "140109": "ä¸‡æŸæž—åŒº",
	    "140110": "æ™‹æºåŒº",
	    "140121": "æ¸…å¾åŽ¿",
	    "140122": "é˜³æ›²åŽ¿",
	    "140123": "å¨„çƒ¦åŽ¿",
	    "140181": "å¤äº¤å¸‚",
	    "140182": "å…¶å®ƒåŒº",
	    "140200": "å¤§åŒå¸‚",
	    "140202": "åŸŽåŒº",
	    "140203": "çŸ¿åŒº",
	    "140211": "å—éƒŠåŒº",
	    "140212": "æ–°è£åŒº",
	    "140221": "é˜³é«˜åŽ¿",
	    "140222": "å¤©é•‡åŽ¿",
	    "140223": "å¹¿çµåŽ¿",
	    "140224": "çµä¸˜åŽ¿",
	    "140225": "æµ‘æºåŽ¿",
	    "140226": "å·¦äº‘åŽ¿",
	    "140227": "å¤§åŒåŽ¿",
	    "140228": "å…¶å®ƒåŒº",
	    "140300": "é˜³æ³‰å¸‚",
	    "140302": "åŸŽåŒº",
	    "140303": "çŸ¿åŒº",
	    "140311": "éƒŠåŒº",
	    "140321": "å¹³å®šåŽ¿",
	    "140322": "ç›‚åŽ¿",
	    "140323": "å…¶å®ƒåŒº",
	    "140400": "é•¿æ²»å¸‚",
	    "140421": "é•¿æ²»åŽ¿",
	    "140423": "è¥„åž£åŽ¿",
	    "140424": "å±¯ç•™åŽ¿",
	    "140425": "å¹³é¡ºåŽ¿",
	    "140426": "é»ŽåŸŽåŽ¿",
	    "140427": "å£¶å…³åŽ¿",
	    "140428": "é•¿å­åŽ¿",
	    "140429": "æ­¦ä¹¡åŽ¿",
	    "140430": "æ²åŽ¿",
	    "140431": "æ²æºåŽ¿",
	    "140481": "æ½žåŸŽå¸‚",
	    "140482": "åŸŽåŒº",
	    "140483": "éƒŠåŒº",
	    "140485": "å…¶å®ƒåŒº",
	    "140500": "æ™‹åŸŽå¸‚",
	    "140502": "åŸŽåŒº",
	    "140521": "æ²æ°´åŽ¿",
	    "140522": "é˜³åŸŽåŽ¿",
	    "140524": "é™µå·åŽ¿",
	    "140525": "æ³½å·žåŽ¿",
	    "140581": "é«˜å¹³å¸‚",
	    "140582": "å…¶å®ƒåŒº",
	    "140600": "æœ”å·žå¸‚",
	    "140602": "æœ”åŸŽåŒº",
	    "140603": "å¹³é²åŒº",
	    "140621": "å±±é˜´åŽ¿",
	    "140622": "åº”åŽ¿",
	    "140623": "å³çŽ‰åŽ¿",
	    "140624": "æ€€ä»åŽ¿",
	    "140625": "å…¶å®ƒåŒº",
	    "140700": "æ™‹ä¸­å¸‚",
	    "140702": "æ¦†æ¬¡åŒº",
	    "140721": "æ¦†ç¤¾åŽ¿",
	    "140722": "å·¦æƒåŽ¿",
	    "140723": "å’Œé¡ºåŽ¿",
	    "140724": "æ˜”é˜³åŽ¿",
	    "140725": "å¯¿é˜³åŽ¿",
	    "140726": "å¤ªè°·åŽ¿",
	    "140727": "ç¥åŽ¿",
	    "140728": "å¹³é¥åŽ¿",
	    "140729": "çµçŸ³åŽ¿",
	    "140781": "ä»‹ä¼‘å¸‚",
	    "140782": "å…¶å®ƒåŒº",
	    "140800": "è¿åŸŽå¸‚",
	    "140802": "ç›æ¹–åŒº",
	    "140821": "ä¸´çŒ—åŽ¿",
	    "140822": "ä¸‡è£åŽ¿",
	    "140823": "é—»å–œåŽ¿",
	    "140824": "ç¨·å±±åŽ¿",
	    "140825": "æ–°ç»›åŽ¿",
	    "140826": "ç»›åŽ¿",
	    "140827": "åž£æ›²åŽ¿",
	    "140828": "å¤åŽ¿",
	    "140829": "å¹³é™†åŽ¿",
	    "140830": "èŠ®åŸŽåŽ¿",
	    "140881": "æ°¸æµŽå¸‚",
	    "140882": "æ²³æ´¥å¸‚",
	    "140883": "å…¶å®ƒåŒº",
	    "140900": "å¿»å·žå¸‚",
	    "140902": "å¿»åºœåŒº",
	    "140921": "å®šè¥„åŽ¿",
	    "140922": "äº”å°åŽ¿",
	    "140923": "ä»£åŽ¿",
	    "140924": "ç¹å³™åŽ¿",
	    "140925": "å®æ­¦åŽ¿",
	    "140926": "é™ä¹åŽ¿",
	    "140927": "ç¥žæ± åŽ¿",
	    "140928": "äº”å¯¨åŽ¿",
	    "140929": "å²¢å²šåŽ¿",
	    "140930": "æ²³æ›²åŽ¿",
	    "140931": "ä¿å¾·åŽ¿",
	    "140932": "åå…³åŽ¿",
	    "140981": "åŽŸå¹³å¸‚",
	    "140982": "å…¶å®ƒåŒº",
	    "141000": "ä¸´æ±¾å¸‚",
	    "141002": "å°§éƒ½åŒº",
	    "141021": "æ›²æ²ƒåŽ¿",
	    "141022": "ç¿¼åŸŽåŽ¿",
	    "141023": "è¥„æ±¾åŽ¿",
	    "141024": "æ´ªæ´žåŽ¿",
	    "141025": "å¤åŽ¿",
	    "141026": "å®‰æ³½åŽ¿",
	    "141027": "æµ®å±±åŽ¿",
	    "141028": "å‰åŽ¿",
	    "141029": "ä¹¡å®åŽ¿",
	    "141030": "å¤§å®åŽ¿",
	    "141031": "éš°åŽ¿",
	    "141032": "æ°¸å’ŒåŽ¿",
	    "141033": "è’²åŽ¿",
	    "141034": "æ±¾è¥¿åŽ¿",
	    "141081": "ä¾¯é©¬å¸‚",
	    "141082": "éœå·žå¸‚",
	    "141083": "å…¶å®ƒåŒº",
	    "141100": "å•æ¢å¸‚",
	    "141102": "ç¦»çŸ³åŒº",
	    "141121": "æ–‡æ°´åŽ¿",
	    "141122": "äº¤åŸŽåŽ¿",
	    "141123": "å…´åŽ¿",
	    "141124": "ä¸´åŽ¿",
	    "141125": "æŸ³æž—åŽ¿",
	    "141126": "çŸ³æ¥¼åŽ¿",
	    "141127": "å²šåŽ¿",
	    "141128": "æ–¹å±±åŽ¿",
	    "141129": "ä¸­é˜³åŽ¿",
	    "141130": "äº¤å£åŽ¿",
	    "141181": "å­ä¹‰å¸‚",
	    "141182": "æ±¾é˜³å¸‚",
	    "141183": "å…¶å®ƒåŒº",
	    "150000": "å†…è’™å¤è‡ªæ²»åŒº",
	    "150100": "å‘¼å’Œæµ©ç‰¹å¸‚",
	    "150102": "æ–°åŸŽåŒº",
	    "150103": "å›žæ°‘åŒº",
	    "150104": "çŽ‰æ³‰åŒº",
	    "150105": "èµ›ç½•åŒº",
	    "150121": "åœŸé»˜ç‰¹å·¦æ——",
	    "150122": "æ‰˜å…‹æ‰˜åŽ¿",
	    "150123": "å’Œæž—æ ¼å°”åŽ¿",
	    "150124": "æ¸…æ°´æ²³åŽ¿",
	    "150125": "æ­¦å·åŽ¿",
	    "150126": "å…¶å®ƒåŒº",
	    "150200": "åŒ…å¤´å¸‚",
	    "150202": "ä¸œæ²³åŒº",
	    "150203": "æ˜†éƒ½ä»‘åŒº",
	    "150204": "é’å±±åŒº",
	    "150205": "çŸ³æ‹åŒº",
	    "150206": "ç™½äº‘é„‚åšçŸ¿åŒº",
	    "150207": "ä¹åŽŸåŒº",
	    "150221": "åœŸé»˜ç‰¹å³æ——",
	    "150222": "å›ºé˜³åŽ¿",
	    "150223": "è¾¾å°”ç½•èŒ‚æ˜Žå®‰è”åˆæ——",
	    "150224": "å…¶å®ƒåŒº",
	    "150300": "ä¹Œæµ·å¸‚",
	    "150302": "æµ·å‹ƒæ¹¾åŒº",
	    "150303": "æµ·å—åŒº",
	    "150304": "ä¹Œè¾¾åŒº",
	    "150305": "å…¶å®ƒåŒº",
	    "150400": "èµ¤å³°å¸‚",
	    "150402": "çº¢å±±åŒº",
	    "150403": "å…ƒå®å±±åŒº",
	    "150404": "æ¾å±±åŒº",
	    "150421": "é˜¿é²ç§‘å°”æ²æ——",
	    "150422": "å·´æž—å·¦æ——",
	    "150423": "å·´æž—å³æ——",
	    "150424": "æž—è¥¿åŽ¿",
	    "150425": "å…‹ä»€å…‹è…¾æ——",
	    "150426": "ç¿ç‰›ç‰¹æ——",
	    "150428": "å–€å–‡æ²æ——",
	    "150429": "å®åŸŽåŽ¿",
	    "150430": "æ•–æ±‰æ——",
	    "150431": "å…¶å®ƒåŒº",
	    "150500": "é€šè¾½å¸‚",
	    "150502": "ç§‘å°”æ²åŒº",
	    "150521": "ç§‘å°”æ²å·¦ç¿¼ä¸­æ——",
	    "150522": "ç§‘å°”æ²å·¦ç¿¼åŽæ——",
	    "150523": "å¼€é²åŽ¿",
	    "150524": "åº“ä¼¦æ——",
	    "150525": "å¥ˆæ›¼æ——",
	    "150526": "æ‰Žé²ç‰¹æ——",
	    "150581": "éœæž—éƒ­å‹’å¸‚",
	    "150582": "å…¶å®ƒåŒº",
	    "150600": "é„‚å°”å¤šæ–¯å¸‚",
	    "150602": "ä¸œèƒœåŒº",
	    "150621": "è¾¾æ‹‰ç‰¹æ——",
	    "150622": "å‡†æ ¼å°”æ——",
	    "150623": "é„‚æ‰˜å…‹å‰æ——",
	    "150624": "é„‚æ‰˜å…‹æ——",
	    "150625": "æ­é”¦æ——",
	    "150626": "ä¹Œå®¡æ——",
	    "150627": "ä¼Šé‡‘éœæ´›æ——",
	    "150628": "å…¶å®ƒåŒº",
	    "150700": "å‘¼ä¼¦è´å°”å¸‚",
	    "150702": "æµ·æ‹‰å°”åŒº",
	    "150703": "æ‰Žèµ‰è¯ºå°”åŒº",
	    "150721": "é˜¿è£æ——",
	    "150722": "èŽ«åŠ›è¾¾ç“¦è¾¾æ–¡å°”æ—è‡ªæ²»æ——",
	    "150723": "é„‚ä¼¦æ˜¥è‡ªæ²»æ——",
	    "150724": "é„‚æ¸©å…‹æ—è‡ªæ²»æ——",
	    "150725": "é™ˆå·´å°”è™Žæ——",
	    "150726": "æ–°å·´å°”è™Žå·¦æ——",
	    "150727": "æ–°å·´å°”è™Žå³æ——",
	    "150781": "æ»¡æ´²é‡Œå¸‚",
	    "150782": "ç‰™å…‹çŸ³å¸‚",
	    "150783": "æ‰Žå…°å±¯å¸‚",
	    "150784": "é¢å°”å¤çº³å¸‚",
	    "150785": "æ ¹æ²³å¸‚",
	    "150786": "å…¶å®ƒåŒº",
	    "150800": "å·´å½¦æ·–å°”å¸‚",
	    "150802": "ä¸´æ²³åŒº",
	    "150821": "äº”åŽŸåŽ¿",
	    "150822": "ç£´å£åŽ¿",
	    "150823": "ä¹Œæ‹‰ç‰¹å‰æ——",
	    "150824": "ä¹Œæ‹‰ç‰¹ä¸­æ——",
	    "150825": "ä¹Œæ‹‰ç‰¹åŽæ——",
	    "150826": "æ­é”¦åŽæ——",
	    "150827": "å…¶å®ƒåŒº",
	    "150900": "ä¹Œå…°å¯Ÿå¸ƒå¸‚",
	    "150902": "é›†å®åŒº",
	    "150921": "å“èµ„åŽ¿",
	    "150922": "åŒ–å¾·åŽ¿",
	    "150923": "å•†éƒ½åŽ¿",
	    "150924": "å…´å’ŒåŽ¿",
	    "150925": "å‡‰åŸŽåŽ¿",
	    "150926": "å¯Ÿå“ˆå°”å³ç¿¼å‰æ——",
	    "150927": "å¯Ÿå“ˆå°”å³ç¿¼ä¸­æ——",
	    "150928": "å¯Ÿå“ˆå°”å³ç¿¼åŽæ——",
	    "150929": "å››å­çŽ‹æ——",
	    "150981": "ä¸°é•‡å¸‚",
	    "150982": "å…¶å®ƒåŒº",
	    "152200": "å…´å®‰ç›Ÿ",
	    "152201": "ä¹Œå…°æµ©ç‰¹å¸‚",
	    "152202": "é˜¿å°”å±±å¸‚",
	    "152221": "ç§‘å°”æ²å³ç¿¼å‰æ——",
	    "152222": "ç§‘å°”æ²å³ç¿¼ä¸­æ——",
	    "152223": "æ‰Žèµ‰ç‰¹æ——",
	    "152224": "çªæ³‰åŽ¿",
	    "152225": "å…¶å®ƒåŒº",
	    "152500": "é”¡æž—éƒ­å‹’ç›Ÿ",
	    "152501": "äºŒè¿žæµ©ç‰¹å¸‚",
	    "152502": "é”¡æž—æµ©ç‰¹å¸‚",
	    "152522": "é˜¿å·´å˜Žæ——",
	    "152523": "è‹å°¼ç‰¹å·¦æ——",
	    "152524": "è‹å°¼ç‰¹å³æ——",
	    "152525": "ä¸œä¹Œç ç©†æ²æ——",
	    "152526": "è¥¿ä¹Œç ç©†æ²æ——",
	    "152527": "å¤ªä»†å¯ºæ——",
	    "152528": "é•¶é»„æ——",
	    "152529": "æ­£é•¶ç™½æ——",
	    "152530": "æ­£è“æ——",
	    "152531": "å¤šä¼¦åŽ¿",
	    "152532": "å…¶å®ƒåŒº",
	    "152900": "é˜¿æ‹‰å–„ç›Ÿ",
	    "152921": "é˜¿æ‹‰å–„å·¦æ——",
	    "152922": "é˜¿æ‹‰å–„å³æ——",
	    "152923": "é¢æµŽçº³æ——",
	    "152924": "å…¶å®ƒåŒº",
	    "210000": "è¾½å®çœ",
	    "210100": "æ²ˆé˜³å¸‚",
	    "210102": "å’Œå¹³åŒº",
	    "210103": "æ²ˆæ²³åŒº",
	    "210104": "å¤§ä¸œåŒº",
	    "210105": "çš‡å§‘åŒº",
	    "210106": "é“è¥¿åŒº",
	    "210111": "è‹å®¶å±¯åŒº",
	    "210112": "ä¸œé™µåŒº",
	    "210113": "æ–°åŸŽå­åŒº",
	    "210114": "äºŽæ´ªåŒº",
	    "210122": "è¾½ä¸­åŽ¿",
	    "210123": "åº·å¹³åŽ¿",
	    "210124": "æ³•åº“åŽ¿",
	    "210181": "æ–°æ°‘å¸‚",
	    "210184": "æ²ˆåŒ—æ–°åŒº",
	    "210185": "å…¶å®ƒåŒº",
	    "210200": "å¤§è¿žå¸‚",
	    "210202": "ä¸­å±±åŒº",
	    "210203": "è¥¿å²—åŒº",
	    "210204": "æ²™æ²³å£åŒº",
	    "210211": "ç”˜äº•å­åŒº",
	    "210212": "æ—…é¡ºå£åŒº",
	    "210213": "é‡‘å·žåŒº",
	    "210224": "é•¿æµ·åŽ¿",
	    "210281": "ç“¦æˆ¿åº—å¸‚",
	    "210282": "æ™®å…°åº—å¸‚",
	    "210283": "åº„æ²³å¸‚",
	    "210298": "å…¶å®ƒåŒº",
	    "210300": "éžå±±å¸‚",
	    "210302": "é“ä¸œåŒº",
	    "210303": "é“è¥¿åŒº",
	    "210304": "ç«‹å±±åŒº",
	    "210311": "åƒå±±åŒº",
	    "210321": "å°å®‰åŽ¿",
	    "210323": "å²«å²©æ»¡æ—è‡ªæ²»åŽ¿",
	    "210381": "æµ·åŸŽå¸‚",
	    "210382": "å…¶å®ƒåŒº",
	    "210400": "æŠšé¡ºå¸‚",
	    "210402": "æ–°æŠšåŒº",
	    "210403": "ä¸œæ´²åŒº",
	    "210404": "æœ›èŠ±åŒº",
	    "210411": "é¡ºåŸŽåŒº",
	    "210421": "æŠšé¡ºåŽ¿",
	    "210422": "æ–°å®¾æ»¡æ—è‡ªæ²»åŽ¿",
	    "210423": "æ¸…åŽŸæ»¡æ—è‡ªæ²»åŽ¿",
	    "210424": "å…¶å®ƒåŒº",
	    "210500": "æœ¬æºªå¸‚",
	    "210502": "å¹³å±±åŒº",
	    "210503": "æºªæ¹–åŒº",
	    "210504": "æ˜Žå±±åŒº",
	    "210505": "å—èŠ¬åŒº",
	    "210521": "æœ¬æºªæ»¡æ—è‡ªæ²»åŽ¿",
	    "210522": "æ¡“ä»æ»¡æ—è‡ªæ²»åŽ¿",
	    "210523": "å…¶å®ƒåŒº",
	    "210600": "ä¸¹ä¸œå¸‚",
	    "210602": "å…ƒå®åŒº",
	    "210603": "æŒ¯å…´åŒº",
	    "210604": "æŒ¯å®‰åŒº",
	    "210624": "å®½ç”¸æ»¡æ—è‡ªæ²»åŽ¿",
	    "210681": "ä¸œæ¸¯å¸‚",
	    "210682": "å‡¤åŸŽå¸‚",
	    "210683": "å…¶å®ƒåŒº",
	    "210700": "é”¦å·žå¸‚",
	    "210702": "å¤å¡”åŒº",
	    "210703": "å‡Œæ²³åŒº",
	    "210711": "å¤ªå’ŒåŒº",
	    "210726": "é»‘å±±åŽ¿",
	    "210727": "ä¹‰åŽ¿",
	    "210781": "å‡Œæµ·å¸‚",
	    "210782": "åŒ—é•‡å¸‚",
	    "210783": "å…¶å®ƒåŒº",
	    "210800": "è¥å£å¸‚",
	    "210802": "ç«™å‰åŒº",
	    "210803": "è¥¿å¸‚åŒº",
	    "210804": "é²…é±¼åœˆåŒº",
	    "210811": "è€è¾¹åŒº",
	    "210881": "ç›–å·žå¸‚",
	    "210882": "å¤§çŸ³æ¡¥å¸‚",
	    "210883": "å…¶å®ƒåŒº",
	    "210900": "é˜œæ–°å¸‚",
	    "210902": "æµ·å·žåŒº",
	    "210903": "æ–°é‚±åŒº",
	    "210904": "å¤ªå¹³åŒº",
	    "210905": "æ¸…æ²³é—¨åŒº",
	    "210911": "ç»†æ²³åŒº",
	    "210921": "é˜œæ–°è’™å¤æ—è‡ªæ²»åŽ¿",
	    "210922": "å½°æ­¦åŽ¿",
	    "210923": "å…¶å®ƒåŒº",
	    "211000": "è¾½é˜³å¸‚",
	    "211002": "ç™½å¡”åŒº",
	    "211003": "æ–‡åœ£åŒº",
	    "211004": "å®ä¼ŸåŒº",
	    "211005": "å¼“é•¿å²­åŒº",
	    "211011": "å¤ªå­æ²³åŒº",
	    "211021": "è¾½é˜³åŽ¿",
	    "211081": "ç¯å¡”å¸‚",
	    "211082": "å…¶å®ƒåŒº",
	    "211100": "ç›˜é”¦å¸‚",
	    "211102": "åŒå°å­åŒº",
	    "211103": "å…´éš†å°åŒº",
	    "211121": "å¤§æ´¼åŽ¿",
	    "211122": "ç›˜å±±åŽ¿",
	    "211123": "å…¶å®ƒåŒº",
	    "211200": "é“å²­å¸‚",
	    "211202": "é“¶å·žåŒº",
	    "211204": "æ¸…æ²³åŒº",
	    "211221": "é“å²­åŽ¿",
	    "211223": "è¥¿ä¸°åŽ¿",
	    "211224": "æ˜Œå›¾åŽ¿",
	    "211281": "è°ƒå…µå±±å¸‚",
	    "211282": "å¼€åŽŸå¸‚",
	    "211283": "å…¶å®ƒåŒº",
	    "211300": "æœé˜³å¸‚",
	    "211302": "åŒå¡”åŒº",
	    "211303": "é¾™åŸŽåŒº",
	    "211321": "æœé˜³åŽ¿",
	    "211322": "å»ºå¹³åŽ¿",
	    "211324": "å–€å–‡æ²å·¦ç¿¼è’™å¤æ—è‡ªæ²»åŽ¿",
	    "211381": "åŒ—ç¥¨å¸‚",
	    "211382": "å‡Œæºå¸‚",
	    "211383": "å…¶å®ƒåŒº",
	    "211400": "è‘«èŠ¦å²›å¸‚",
	    "211402": "è¿žå±±åŒº",
	    "211403": "é¾™æ¸¯åŒº",
	    "211404": "å—ç¥¨åŒº",
	    "211421": "ç»¥ä¸­åŽ¿",
	    "211422": "å»ºæ˜ŒåŽ¿",
	    "211481": "å…´åŸŽå¸‚",
	    "211482": "å…¶å®ƒåŒº",
	    "220000": "å‰æž—çœ",
	    "220100": "é•¿æ˜¥å¸‚",
	    "220102": "å—å…³åŒº",
	    "220103": "å®½åŸŽåŒº",
	    "220104": "æœé˜³åŒº",
	    "220105": "äºŒé“åŒº",
	    "220106": "ç»¿å›­åŒº",
	    "220112": "åŒé˜³åŒº",
	    "220122": "å†œå®‰åŽ¿",
	    "220181": "ä¹å°å¸‚",
	    "220182": "æ¦†æ ‘å¸‚",
	    "220183": "å¾·æƒ å¸‚",
	    "220188": "å…¶å®ƒåŒº",
	    "220200": "å‰æž—å¸‚",
	    "220202": "æ˜Œé‚‘åŒº",
	    "220203": "é¾™æ½­åŒº",
	    "220204": "èˆ¹è¥åŒº",
	    "220211": "ä¸°æ»¡åŒº",
	    "220221": "æ°¸å‰åŽ¿",
	    "220281": "è›Ÿæ²³å¸‚",
	    "220282": "æ¡¦ç”¸å¸‚",
	    "220283": "èˆ’å…°å¸‚",
	    "220284": "ç£çŸ³å¸‚",
	    "220285": "å…¶å®ƒåŒº",
	    "220300": "å››å¹³å¸‚",
	    "220302": "é“è¥¿åŒº",
	    "220303": "é“ä¸œåŒº",
	    "220322": "æ¢¨æ ‘åŽ¿",
	    "220323": "ä¼Šé€šæ»¡æ—è‡ªæ²»åŽ¿",
	    "220381": "å…¬ä¸»å²­å¸‚",
	    "220382": "åŒè¾½å¸‚",
	    "220383": "å…¶å®ƒåŒº",
	    "220400": "è¾½æºå¸‚",
	    "220402": "é¾™å±±åŒº",
	    "220403": "è¥¿å®‰åŒº",
	    "220421": "ä¸œä¸°åŽ¿",
	    "220422": "ä¸œè¾½åŽ¿",
	    "220423": "å…¶å®ƒåŒº",
	    "220500": "é€šåŒ–å¸‚",
	    "220502": "ä¸œæ˜ŒåŒº",
	    "220503": "äºŒé“æ±ŸåŒº",
	    "220521": "é€šåŒ–åŽ¿",
	    "220523": "è¾‰å—åŽ¿",
	    "220524": "æŸ³æ²³åŽ¿",
	    "220581": "æ¢…æ²³å£å¸‚",
	    "220582": "é›†å®‰å¸‚",
	    "220583": "å…¶å®ƒåŒº",
	    "220600": "ç™½å±±å¸‚",
	    "220602": "æµ‘æ±ŸåŒº",
	    "220621": "æŠšæ¾åŽ¿",
	    "220622": "é–å®‡åŽ¿",
	    "220623": "é•¿ç™½æœé²œæ—è‡ªæ²»åŽ¿",
	    "220625": "æ±ŸæºåŒº",
	    "220681": "ä¸´æ±Ÿå¸‚",
	    "220682": "å…¶å®ƒåŒº",
	    "220700": "æ¾åŽŸå¸‚",
	    "220702": "å®æ±ŸåŒº",
	    "220721": "å‰éƒ­å°”ç½—æ–¯è’™å¤æ—è‡ªæ²»åŽ¿",
	    "220722": "é•¿å²­åŽ¿",
	    "220723": "ä¹¾å®‰åŽ¿",
	    "220724": "æ‰¶ä½™å¸‚",
	    "220725": "å…¶å®ƒåŒº",
	    "220800": "ç™½åŸŽå¸‚",
	    "220802": "æ´®åŒ—åŒº",
	    "220821": "é•‡èµ‰åŽ¿",
	    "220822": "é€šæ¦†åŽ¿",
	    "220881": "æ´®å—å¸‚",
	    "220882": "å¤§å®‰å¸‚",
	    "220883": "å…¶å®ƒåŒº",
	    "222400": "å»¶è¾¹æœé²œæ—è‡ªæ²»å·ž",
	    "222401": "å»¶å‰å¸‚",
	    "222402": "å›¾ä»¬å¸‚",
	    "222403": "æ•¦åŒ–å¸‚",
	    "222404": "ç²æ˜¥å¸‚",
	    "222405": "é¾™äº•å¸‚",
	    "222406": "å’Œé¾™å¸‚",
	    "222424": "æ±ªæ¸…åŽ¿",
	    "222426": "å®‰å›¾åŽ¿",
	    "222427": "å…¶å®ƒåŒº",
	    "230000": "é»‘é¾™æ±Ÿçœ",
	    "230100": "å“ˆå°”æ»¨å¸‚",
	    "230102": "é“é‡ŒåŒº",
	    "230103": "å—å²—åŒº",
	    "230104": "é“å¤–åŒº",
	    "230106": "é¦™åŠåŒº",
	    "230108": "å¹³æˆ¿åŒº",
	    "230109": "æ¾åŒ—åŒº",
	    "230111": "å‘¼å…°åŒº",
	    "230123": "ä¾å…°åŽ¿",
	    "230124": "æ–¹æ­£åŽ¿",
	    "230125": "å®¾åŽ¿",
	    "230126": "å·´å½¦åŽ¿",
	    "230127": "æœ¨å…°åŽ¿",
	    "230128": "é€šæ²³åŽ¿",
	    "230129": "å»¶å¯¿åŽ¿",
	    "230181": "é˜¿åŸŽåŒº",
	    "230182": "åŒåŸŽå¸‚",
	    "230183": "å°šå¿—å¸‚",
	    "230184": "äº”å¸¸å¸‚",
	    "230186": "å…¶å®ƒåŒº",
	    "230200": "é½é½å“ˆå°”å¸‚",
	    "230202": "é¾™æ²™åŒº",
	    "230203": "å»ºåŽåŒº",
	    "230204": "é“é”‹åŒº",
	    "230205": "æ˜‚æ˜‚æºªåŒº",
	    "230206": "å¯Œæ‹‰å°”åŸºåŒº",
	    "230207": "ç¢¾å­å±±åŒº",
	    "230208": "æ¢…é‡Œæ–¯è¾¾æ–¡å°”æ—åŒº",
	    "230221": "é¾™æ±ŸåŽ¿",
	    "230223": "ä¾å®‰åŽ¿",
	    "230224": "æ³°æ¥åŽ¿",
	    "230225": "ç”˜å—åŽ¿",
	    "230227": "å¯Œè£•åŽ¿",
	    "230229": "å…‹å±±åŽ¿",
	    "230230": "å…‹ä¸œåŽ¿",
	    "230231": "æ‹œæ³‰åŽ¿",
	    "230281": "è®·æ²³å¸‚",
	    "230282": "å…¶å®ƒåŒº",
	    "230300": "é¸¡è¥¿å¸‚",
	    "230302": "é¸¡å† åŒº",
	    "230303": "æ’å±±åŒº",
	    "230304": "æ»´é“åŒº",
	    "230305": "æ¢¨æ ‘åŒº",
	    "230306": "åŸŽå­æ²³åŒº",
	    "230307": "éº»å±±åŒº",
	    "230321": "é¸¡ä¸œåŽ¿",
	    "230381": "è™Žæž—å¸‚",
	    "230382": "å¯†å±±å¸‚",
	    "230383": "å…¶å®ƒåŒº",
	    "230400": "é¹¤å²—å¸‚",
	    "230402": "å‘é˜³åŒº",
	    "230403": "å·¥å†œåŒº",
	    "230404": "å—å±±åŒº",
	    "230405": "å…´å®‰åŒº",
	    "230406": "ä¸œå±±åŒº",
	    "230407": "å…´å±±åŒº",
	    "230421": "èåŒ—åŽ¿",
	    "230422": "ç»¥æ»¨åŽ¿",
	    "230423": "å…¶å®ƒåŒº",
	    "230500": "åŒé¸­å±±å¸‚",
	    "230502": "å°–å±±åŒº",
	    "230503": "å²­ä¸œåŒº",
	    "230505": "å››æ–¹å°åŒº",
	    "230506": "å®å±±åŒº",
	    "230521": "é›†è´¤åŽ¿",
	    "230522": "å‹è°ŠåŽ¿",
	    "230523": "å®æ¸…åŽ¿",
	    "230524": "é¥¶æ²³åŽ¿",
	    "230525": "å…¶å®ƒåŒº",
	    "230600": "å¤§åº†å¸‚",
	    "230602": "è¨å°”å›¾åŒº",
	    "230603": "é¾™å‡¤åŒº",
	    "230604": "è®©èƒ¡è·¯åŒº",
	    "230605": "çº¢å²—åŒº",
	    "230606": "å¤§åŒåŒº",
	    "230621": "è‚‡å·žåŽ¿",
	    "230622": "è‚‡æºåŽ¿",
	    "230623": "æž—ç”¸åŽ¿",
	    "230624": "æœå°”ä¼¯ç‰¹è’™å¤æ—è‡ªæ²»åŽ¿",
	    "230625": "å…¶å®ƒåŒº",
	    "230700": "ä¼Šæ˜¥å¸‚",
	    "230702": "ä¼Šæ˜¥åŒº",
	    "230703": "å—å²”åŒº",
	    "230704": "å‹å¥½åŒº",
	    "230705": "è¥¿æž—åŒº",
	    "230706": "ç¿ å³¦åŒº",
	    "230707": "æ–°é’åŒº",
	    "230708": "ç¾ŽæºªåŒº",
	    "230709": "é‡‘å±±å±¯åŒº",
	    "230710": "äº”è¥åŒº",
	    "230711": "ä¹Œé©¬æ²³åŒº",
	    "230712": "æ±¤æ—ºæ²³åŒº",
	    "230713": "å¸¦å²­åŒº",
	    "230714": "ä¹Œä¼Šå²­åŒº",
	    "230715": "çº¢æ˜ŸåŒº",
	    "230716": "ä¸Šç”˜å²­åŒº",
	    "230722": "å˜‰è«åŽ¿",
	    "230781": "é“åŠ›å¸‚",
	    "230782": "å…¶å®ƒåŒº",
	    "230800": "ä½³æœ¨æ–¯å¸‚",
	    "230803": "å‘é˜³åŒº",
	    "230804": "å‰è¿›åŒº",
	    "230805": "ä¸œé£ŽåŒº",
	    "230811": "éƒŠåŒº",
	    "230822": "æ¡¦å—åŽ¿",
	    "230826": "æ¡¦å·åŽ¿",
	    "230828": "æ±¤åŽŸåŽ¿",
	    "230833": "æŠšè¿œåŽ¿",
	    "230881": "åŒæ±Ÿå¸‚",
	    "230882": "å¯Œé”¦å¸‚",
	    "230883": "å…¶å®ƒåŒº",
	    "230900": "ä¸ƒå°æ²³å¸‚",
	    "230902": "æ–°å…´åŒº",
	    "230903": "æ¡ƒå±±åŒº",
	    "230904": "èŒ„å­æ²³åŒº",
	    "230921": "å‹ƒåˆ©åŽ¿",
	    "230922": "å…¶å®ƒåŒº",
	    "231000": "ç‰¡ä¸¹æ±Ÿå¸‚",
	    "231002": "ä¸œå®‰åŒº",
	    "231003": "é˜³æ˜ŽåŒº",
	    "231004": "çˆ±æ°‘åŒº",
	    "231005": "è¥¿å®‰åŒº",
	    "231024": "ä¸œå®åŽ¿",
	    "231025": "æž—å£åŽ¿",
	    "231081": "ç»¥èŠ¬æ²³å¸‚",
	    "231083": "æµ·æž—å¸‚",
	    "231084": "å®å®‰å¸‚",
	    "231085": "ç©†æ£±å¸‚",
	    "231086": "å…¶å®ƒåŒº",
	    "231100": "é»‘æ²³å¸‚",
	    "231102": "çˆ±è¾‰åŒº",
	    "231121": "å«©æ±ŸåŽ¿",
	    "231123": "é€Šå…‹åŽ¿",
	    "231124": "å­™å´åŽ¿",
	    "231181": "åŒ—å®‰å¸‚",
	    "231182": "äº”å¤§è¿žæ± å¸‚",
	    "231183": "å…¶å®ƒåŒº",
	    "231200": "ç»¥åŒ–å¸‚",
	    "231202": "åŒ—æž—åŒº",
	    "231221": "æœ›å¥ŽåŽ¿",
	    "231222": "å…°è¥¿åŽ¿",
	    "231223": "é’å†ˆåŽ¿",
	    "231224": "åº†å®‰åŽ¿",
	    "231225": "æ˜Žæ°´åŽ¿",
	    "231226": "ç»¥æ£±åŽ¿",
	    "231281": "å®‰è¾¾å¸‚",
	    "231282": "è‚‡ä¸œå¸‚",
	    "231283": "æµ·ä¼¦å¸‚",
	    "231284": "å…¶å®ƒåŒº",
	    "232700": "å¤§å…´å®‰å²­åœ°åŒº",
	    "232702": "æ¾å²­åŒº",
	    "232703": "æ–°æž—åŒº",
	    "232704": "å‘¼ä¸­åŒº",
	    "232721": "å‘¼çŽ›åŽ¿",
	    "232722": "å¡”æ²³åŽ¿",
	    "232723": "æ¼ æ²³åŽ¿",
	    "232724": "åŠ æ ¼è¾¾å¥‡åŒº",
	    "232725": "å…¶å®ƒåŒº",
	    "310000": "ä¸Šæµ·",
	    "310100": "ä¸Šæµ·å¸‚",
	    "310101": "é»„æµ¦åŒº",
	    "310104": "å¾æ±‡åŒº",
	    "310105": "é•¿å®åŒº",
	    "310106": "é™å®‰åŒº",
	    "310107": "æ™®é™€åŒº",
	    "310108": "é—¸åŒ—åŒº",
	    "310109": "è™¹å£åŒº",
	    "310110": "æ¨æµ¦åŒº",
	    "310112": "é—µè¡ŒåŒº",
	    "310113": "å®å±±åŒº",
	    "310114": "å˜‰å®šåŒº",
	    "310115": "æµ¦ä¸œæ–°åŒº",
	    "310116": "é‡‘å±±åŒº",
	    "310117": "æ¾æ±ŸåŒº",
	    "310118": "é’æµ¦åŒº",
	    "310120": "å¥‰è´¤åŒº",
	    "310230": "å´‡æ˜ŽåŽ¿",
	    "310231": "å…¶å®ƒåŒº",
	    "320000": "æ±Ÿè‹çœ",
	    "320100": "å—äº¬å¸‚",
	    "320102": "çŽ„æ­¦åŒº",
	    "320104": "ç§¦æ·®åŒº",
	    "320105": "å»ºé‚ºåŒº",
	    "320106": "é¼“æ¥¼åŒº",
	    "320111": "æµ¦å£åŒº",
	    "320113": "æ –éœžåŒº",
	    "320114": "é›¨èŠ±å°åŒº",
	    "320115": "æ±Ÿå®åŒº",
	    "320116": "å…­åˆåŒº",
	    "320124": "æº§æ°´åŒº",
	    "320125": "é«˜æ·³åŒº",
	    "320126": "å…¶å®ƒåŒº",
	    "320200": "æ— é”¡å¸‚",
	    "320202": "å´‡å®‰åŒº",
	    "320203": "å—é•¿åŒº",
	    "320204": "åŒ—å¡˜åŒº",
	    "320205": "é”¡å±±åŒº",
	    "320206": "æƒ å±±åŒº",
	    "320211": "æ»¨æ¹–åŒº",
	    "320281": "æ±Ÿé˜´å¸‚",
	    "320282": "å®œå…´å¸‚",
	    "320297": "å…¶å®ƒåŒº",
	    "320300": "å¾å·žå¸‚",
	    "320302": "é¼“æ¥¼åŒº",
	    "320303": "äº‘é¾™åŒº",
	    "320305": "è´¾æ±ªåŒº",
	    "320311": "æ³‰å±±åŒº",
	    "320321": "ä¸°åŽ¿",
	    "320322": "æ²›åŽ¿",
	    "320323": "é“œå±±åŒº",
	    "320324": "ç¢å®åŽ¿",
	    "320381": "æ–°æ²‚å¸‚",
	    "320382": "é‚³å·žå¸‚",
	    "320383": "å…¶å®ƒåŒº",
	    "320400": "å¸¸å·žå¸‚",
	    "320402": "å¤©å®åŒº",
	    "320404": "é’Ÿæ¥¼åŒº",
	    "320405": "æˆšå¢…å °åŒº",
	    "320411": "æ–°åŒ—åŒº",
	    "320412": "æ­¦è¿›åŒº",
	    "320481": "æº§é˜³å¸‚",
	    "320482": "é‡‘å›å¸‚",
	    "320483": "å…¶å®ƒåŒº",
	    "320500": "è‹å·žå¸‚",
	    "320505": "è™Žä¸˜åŒº",
	    "320506": "å´ä¸­åŒº",
	    "320507": "ç›¸åŸŽåŒº",
	    "320508": "å§‘è‹åŒº",
	    "320581": "å¸¸ç†Ÿå¸‚",
	    "320582": "å¼ å®¶æ¸¯å¸‚",
	    "320583": "æ˜†å±±å¸‚",
	    "320584": "å´æ±ŸåŒº",
	    "320585": "å¤ªä»“å¸‚",
	    "320596": "å…¶å®ƒåŒº",
	    "320600": "å—é€šå¸‚",
	    "320602": "å´‡å·åŒº",
	    "320611": "æ¸¯é—¸åŒº",
	    "320612": "é€šå·žåŒº",
	    "320621": "æµ·å®‰åŽ¿",
	    "320623": "å¦‚ä¸œåŽ¿",
	    "320681": "å¯ä¸œå¸‚",
	    "320682": "å¦‚çš‹å¸‚",
	    "320684": "æµ·é—¨å¸‚",
	    "320694": "å…¶å®ƒåŒº",
	    "320700": "è¿žäº‘æ¸¯å¸‚",
	    "320703": "è¿žäº‘åŒº",
	    "320705": "æ–°æµ¦åŒº",
	    "320706": "æµ·å·žåŒº",
	    "320721": "èµ£æ¦†åŽ¿",
	    "320722": "ä¸œæµ·åŽ¿",
	    "320723": "çŒäº‘åŽ¿",
	    "320724": "çŒå—åŽ¿",
	    "320725": "å…¶å®ƒåŒº",
	    "320800": "æ·®å®‰å¸‚",
	    "320802": "æ¸…æ²³åŒº",
	    "320803": "æ·®å®‰åŒº",
	    "320804": "æ·®é˜´åŒº",
	    "320811": "æ¸…æµ¦åŒº",
	    "320826": "æ¶Ÿæ°´åŽ¿",
	    "320829": "æ´ªæ³½åŽ¿",
	    "320830": "ç›±çœ™åŽ¿",
	    "320831": "é‡‘æ¹–åŽ¿",
	    "320832": "å…¶å®ƒåŒº",
	    "320900": "ç›åŸŽå¸‚",
	    "320902": "äº­æ¹–åŒº",
	    "320903": "ç›éƒ½åŒº",
	    "320921": "å“æ°´åŽ¿",
	    "320922": "æ»¨æµ·åŽ¿",
	    "320923": "é˜œå®åŽ¿",
	    "320924": "å°„é˜³åŽ¿",
	    "320925": "å»ºæ¹–åŽ¿",
	    "320981": "ä¸œå°å¸‚",
	    "320982": "å¤§ä¸°å¸‚",
	    "320983": "å…¶å®ƒåŒº",
	    "321000": "æ‰¬å·žå¸‚",
	    "321002": "å¹¿é™µåŒº",
	    "321003": "é‚—æ±ŸåŒº",
	    "321023": "å®åº”åŽ¿",
	    "321081": "ä»ªå¾å¸‚",
	    "321084": "é«˜é‚®å¸‚",
	    "321088": "æ±Ÿéƒ½åŒº",
	    "321093": "å…¶å®ƒåŒº",
	    "321100": "é•‡æ±Ÿå¸‚",
	    "321102": "äº¬å£åŒº",
	    "321111": "æ¶¦å·žåŒº",
	    "321112": "ä¸¹å¾’åŒº",
	    "321181": "ä¸¹é˜³å¸‚",
	    "321182": "æ‰¬ä¸­å¸‚",
	    "321183": "å¥å®¹å¸‚",
	    "321184": "å…¶å®ƒåŒº",
	    "321200": "æ³°å·žå¸‚",
	    "321202": "æµ·é™µåŒº",
	    "321203": "é«˜æ¸¯åŒº",
	    "321281": "å…´åŒ–å¸‚",
	    "321282": "é–æ±Ÿå¸‚",
	    "321283": "æ³°å…´å¸‚",
	    "321284": "å§œå °åŒº",
	    "321285": "å…¶å®ƒåŒº",
	    "321300": "å®¿è¿å¸‚",
	    "321302": "å®¿åŸŽåŒº",
	    "321311": "å®¿è±«åŒº",
	    "321322": "æ²­é˜³åŽ¿",
	    "321323": "æ³—é˜³åŽ¿",
	    "321324": "æ³—æ´ªåŽ¿",
	    "321325": "å…¶å®ƒåŒº",
	    "330000": "æµ™æ±Ÿçœ",
	    "330100": "æ­å·žå¸‚",
	    "330102": "ä¸ŠåŸŽåŒº",
	    "330103": "ä¸‹åŸŽåŒº",
	    "330104": "æ±Ÿå¹²åŒº",
	    "330105": "æ‹±å¢…åŒº",
	    "330106": "è¥¿æ¹–åŒº",
	    "330108": "æ»¨æ±ŸåŒº",
	    "330109": "è§å±±åŒº",
	    "330110": "ä½™æ­åŒº",
	    "330122": "æ¡åºåŽ¿",
	    "330127": "æ·³å®‰åŽ¿",
	    "330182": "å»ºå¾·å¸‚",
	    "330183": "å¯Œé˜³å¸‚",
	    "330185": "ä¸´å®‰å¸‚",
	    "330186": "å…¶å®ƒåŒº",
	    "330200": "å®æ³¢å¸‚",
	    "330203": "æµ·æ›™åŒº",
	    "330204": "æ±Ÿä¸œåŒº",
	    "330205": "æ±ŸåŒ—åŒº",
	    "330206": "åŒ—ä»‘åŒº",
	    "330211": "é•‡æµ·åŒº",
	    "330212": "é„žå·žåŒº",
	    "330225": "è±¡å±±åŽ¿",
	    "330226": "å®æµ·åŽ¿",
	    "330281": "ä½™å§šå¸‚",
	    "330282": "æ…ˆæºªå¸‚",
	    "330283": "å¥‰åŒ–å¸‚",
	    "330284": "å…¶å®ƒåŒº",
	    "330300": "æ¸©å·žå¸‚",
	    "330302": "é¹¿åŸŽåŒº",
	    "330303": "é¾™æ¹¾åŒº",
	    "330304": "ç“¯æµ·åŒº",
	    "330322": "æ´žå¤´åŽ¿",
	    "330324": "æ°¸å˜‰åŽ¿",
	    "330326": "å¹³é˜³åŽ¿",
	    "330327": "è‹å—åŽ¿",
	    "330328": "æ–‡æˆåŽ¿",
	    "330329": "æ³°é¡ºåŽ¿",
	    "330381": "ç‘žå®‰å¸‚",
	    "330382": "ä¹æ¸…å¸‚",
	    "330383": "å…¶å®ƒåŒº",
	    "330400": "å˜‰å…´å¸‚",
	    "330402": "å—æ¹–åŒº",
	    "330411": "ç§€æ´²åŒº",
	    "330421": "å˜‰å–„åŽ¿",
	    "330424": "æµ·ç›åŽ¿",
	    "330481": "æµ·å®å¸‚",
	    "330482": "å¹³æ¹–å¸‚",
	    "330483": "æ¡ä¹¡å¸‚",
	    "330484": "å…¶å®ƒåŒº",
	    "330500": "æ¹–å·žå¸‚",
	    "330502": "å´å…´åŒº",
	    "330503": "å—æµ”åŒº",
	    "330521": "å¾·æ¸…åŽ¿",
	    "330522": "é•¿å…´åŽ¿",
	    "330523": "å®‰å‰åŽ¿",
	    "330524": "å…¶å®ƒåŒº",
	    "330600": "ç»å…´å¸‚",
	    "330602": "è¶ŠåŸŽåŒº",
	    "330621": "ç»å…´åŽ¿",
	    "330624": "æ–°æ˜ŒåŽ¿",
	    "330681": "è¯¸æš¨å¸‚",
	    "330682": "ä¸Šè™žå¸‚",
	    "330683": "åµŠå·žå¸‚",
	    "330684": "å…¶å®ƒåŒº",
	    "330700": "é‡‘åŽå¸‚",
	    "330702": "å©ºåŸŽåŒº",
	    "330703": "é‡‘ä¸œåŒº",
	    "330723": "æ­¦ä¹‰åŽ¿",
	    "330726": "æµ¦æ±ŸåŽ¿",
	    "330727": "ç£å®‰åŽ¿",
	    "330781": "å…°æºªå¸‚",
	    "330782": "ä¹‰ä¹Œå¸‚",
	    "330783": "ä¸œé˜³å¸‚",
	    "330784": "æ°¸åº·å¸‚",
	    "330785": "å…¶å®ƒåŒº",
	    "330800": "è¡¢å·žå¸‚",
	    "330802": "æŸ¯åŸŽåŒº",
	    "330803": "è¡¢æ±ŸåŒº",
	    "330822": "å¸¸å±±åŽ¿",
	    "330824": "å¼€åŒ–åŽ¿",
	    "330825": "é¾™æ¸¸åŽ¿",
	    "330881": "æ±Ÿå±±å¸‚",
	    "330882": "å…¶å®ƒåŒº",
	    "330900": "èˆŸå±±å¸‚",
	    "330902": "å®šæµ·åŒº",
	    "330903": "æ™®é™€åŒº",
	    "330921": "å²±å±±åŽ¿",
	    "330922": "åµŠæ³—åŽ¿",
	    "330923": "å…¶å®ƒåŒº",
	    "331000": "å°å·žå¸‚",
	    "331002": "æ¤’æ±ŸåŒº",
	    "331003": "é»„å²©åŒº",
	    "331004": "è·¯æ¡¥åŒº",
	    "331021": "çŽ‰çŽ¯åŽ¿",
	    "331022": "ä¸‰é—¨åŽ¿",
	    "331023": "å¤©å°åŽ¿",
	    "331024": "ä»™å±…åŽ¿",
	    "331081": "æ¸©å²­å¸‚",
	    "331082": "ä¸´æµ·å¸‚",
	    "331083": "å…¶å®ƒåŒº",
	    "331100": "ä¸½æ°´å¸‚",
	    "331102": "èŽ²éƒ½åŒº",
	    "331121": "é’ç”°åŽ¿",
	    "331122": "ç¼™äº‘åŽ¿",
	    "331123": "é‚æ˜ŒåŽ¿",
	    "331124": "æ¾é˜³åŽ¿",
	    "331125": "äº‘å’ŒåŽ¿",
	    "331126": "åº†å…ƒåŽ¿",
	    "331127": "æ™¯å®ç•²æ—è‡ªæ²»åŽ¿",
	    "331181": "é¾™æ³‰å¸‚",
	    "331182": "å…¶å®ƒåŒº",
	    "340000": "å®‰å¾½çœ",
	    "340100": "åˆè‚¥å¸‚",
	    "340102": "ç‘¶æµ·åŒº",
	    "340103": "åºé˜³åŒº",
	    "340104": "èœ€å±±åŒº",
	    "340111": "åŒ…æ²³åŒº",
	    "340121": "é•¿ä¸°åŽ¿",
	    "340122": "è‚¥ä¸œåŽ¿",
	    "340123": "è‚¥è¥¿åŽ¿",
	    "340192": "å…¶å®ƒåŒº",
	    "340200": "èŠœæ¹–å¸‚",
	    "340202": "é•œæ¹–åŒº",
	    "340203": "å¼‹æ±ŸåŒº",
	    "340207": "é¸ æ±ŸåŒº",
	    "340208": "ä¸‰å±±åŒº",
	    "340221": "èŠœæ¹–åŽ¿",
	    "340222": "ç¹æ˜ŒåŽ¿",
	    "340223": "å—é™µåŽ¿",
	    "340224": "å…¶å®ƒåŒº",
	    "340300": "èšŒåŸ å¸‚",
	    "340302": "é¾™å­æ¹–åŒº",
	    "340303": "èšŒå±±åŒº",
	    "340304": "ç¦¹ä¼šåŒº",
	    "340311": "æ·®ä¸ŠåŒº",
	    "340321": "æ€€è¿œåŽ¿",
	    "340322": "äº”æ²³åŽ¿",
	    "340323": "å›ºé•‡åŽ¿",
	    "340324": "å…¶å®ƒåŒº",
	    "340400": "æ·®å—å¸‚",
	    "340402": "å¤§é€šåŒº",
	    "340403": "ç”°å®¶åºµåŒº",
	    "340404": "è°¢å®¶é›†åŒº",
	    "340405": "å…«å…¬å±±åŒº",
	    "340406": "æ½˜é›†åŒº",
	    "340421": "å‡¤å°åŽ¿",
	    "340422": "å…¶å®ƒåŒº",
	    "340500": "é©¬éžå±±å¸‚",
	    "340503": "èŠ±å±±åŒº",
	    "340504": "é›¨å±±åŒº",
	    "340506": "åšæœ›åŒº",
	    "340521": "å½“æ¶‚åŽ¿",
	    "340522": "å…¶å®ƒåŒº",
	    "340600": "æ·®åŒ—å¸‚",
	    "340602": "æœé›†åŒº",
	    "340603": "ç›¸å±±åŒº",
	    "340604": "çƒˆå±±åŒº",
	    "340621": "æ¿‰æºªåŽ¿",
	    "340622": "å…¶å®ƒåŒº",
	    "340700": "é“œé™µå¸‚",
	    "340702": "é“œå®˜å±±åŒº",
	    "340703": "ç‹®å­å±±åŒº",
	    "340711": "éƒŠåŒº",
	    "340721": "é“œé™µåŽ¿",
	    "340722": "å…¶å®ƒåŒº",
	    "340800": "å®‰åº†å¸‚",
	    "340802": "è¿Žæ±ŸåŒº",
	    "340803": "å¤§è§‚åŒº",
	    "340811": "å®œç§€åŒº",
	    "340822": "æ€€å®åŽ¿",
	    "340823": "æžžé˜³åŽ¿",
	    "340824": "æ½œå±±åŽ¿",
	    "340825": "å¤ªæ¹–åŽ¿",
	    "340826": "å®¿æ¾åŽ¿",
	    "340827": "æœ›æ±ŸåŽ¿",
	    "340828": "å²³è¥¿åŽ¿",
	    "340881": "æ¡åŸŽå¸‚",
	    "340882": "å…¶å®ƒåŒº",
	    "341000": "é»„å±±å¸‚",
	    "341002": "å±¯æºªåŒº",
	    "341003": "é»„å±±åŒº",
	    "341004": "å¾½å·žåŒº",
	    "341021": "æ­™åŽ¿",
	    "341022": "ä¼‘å®åŽ¿",
	    "341023": "é»ŸåŽ¿",
	    "341024": "ç¥é—¨åŽ¿",
	    "341025": "å…¶å®ƒåŒº",
	    "341100": "æ»å·žå¸‚",
	    "341102": "ç…çŠåŒº",
	    "341103": "å—è°¯åŒº",
	    "341122": "æ¥å®‰åŽ¿",
	    "341124": "å…¨æ¤’åŽ¿",
	    "341125": "å®šè¿œåŽ¿",
	    "341126": "å‡¤é˜³åŽ¿",
	    "341181": "å¤©é•¿å¸‚",
	    "341182": "æ˜Žå…‰å¸‚",
	    "341183": "å…¶å®ƒåŒº",
	    "341200": "é˜œé˜³å¸‚",
	    "341202": "é¢å·žåŒº",
	    "341203": "é¢ä¸œåŒº",
	    "341204": "é¢æ³‰åŒº",
	    "341221": "ä¸´æ³‰åŽ¿",
	    "341222": "å¤ªå’ŒåŽ¿",
	    "341225": "é˜œå—åŽ¿",
	    "341226": "é¢ä¸ŠåŽ¿",
	    "341282": "ç•Œé¦–å¸‚",
	    "341283": "å…¶å®ƒåŒº",
	    "341300": "å®¿å·žå¸‚",
	    "341302": "åŸ‡æ¡¥åŒº",
	    "341321": "ç €å±±åŽ¿",
	    "341322": "è§åŽ¿",
	    "341323": "çµç’§åŽ¿",
	    "341324": "æ³—åŽ¿",
	    "341325": "å…¶å®ƒåŒº",
	    "341400": "å·¢æ¹–å¸‚",
	    "341421": "åºæ±ŸåŽ¿",
	    "341422": "æ— ä¸ºåŽ¿",
	    "341423": "å«å±±åŽ¿",
	    "341424": "å’ŒåŽ¿",
	    "341500": "å…­å®‰å¸‚",
	    "341502": "é‡‘å®‰åŒº",
	    "341503": "è£•å®‰åŒº",
	    "341521": "å¯¿åŽ¿",
	    "341522": "éœé‚±åŽ¿",
	    "341523": "èˆ’åŸŽåŽ¿",
	    "341524": "é‡‘å¯¨åŽ¿",
	    "341525": "éœå±±åŽ¿",
	    "341526": "å…¶å®ƒåŒº",
	    "341600": "äº³å·žå¸‚",
	    "341602": "è°¯åŸŽåŒº",
	    "341621": "æ¶¡é˜³åŽ¿",
	    "341622": "è’™åŸŽåŽ¿",
	    "341623": "åˆ©è¾›åŽ¿",
	    "341624": "å…¶å®ƒåŒº",
	    "341700": "æ± å·žå¸‚",
	    "341702": "è´µæ± åŒº",
	    "341721": "ä¸œè‡³åŽ¿",
	    "341722": "çŸ³å°åŽ¿",
	    "341723": "é’é˜³åŽ¿",
	    "341724": "å…¶å®ƒåŒº",
	    "341800": "å®£åŸŽå¸‚",
	    "341802": "å®£å·žåŒº",
	    "341821": "éƒŽæºªåŽ¿",
	    "341822": "å¹¿å¾·åŽ¿",
	    "341823": "æ³¾åŽ¿",
	    "341824": "ç»©æºªåŽ¿",
	    "341825": "æ—Œå¾·åŽ¿",
	    "341881": "å®å›½å¸‚",
	    "341882": "å…¶å®ƒåŒº",
	    "350000": "ç¦å»ºçœ",
	    "350100": "ç¦å·žå¸‚",
	    "350102": "é¼“æ¥¼åŒº",
	    "350103": "å°æ±ŸåŒº",
	    "350104": "ä»“å±±åŒº",
	    "350105": "é©¬å°¾åŒº",
	    "350111": "æ™‹å®‰åŒº",
	    "350121": "é—½ä¾¯åŽ¿",
	    "350122": "è¿žæ±ŸåŽ¿",
	    "350123": "ç½—æºåŽ¿",
	    "350124": "é—½æ¸…åŽ¿",
	    "350125": "æ°¸æ³°åŽ¿",
	    "350128": "å¹³æ½­åŽ¿",
	    "350181": "ç¦æ¸…å¸‚",
	    "350182": "é•¿ä¹å¸‚",
	    "350183": "å…¶å®ƒåŒº",
	    "350200": "åŽ¦é—¨å¸‚",
	    "350203": "æ€æ˜ŽåŒº",
	    "350205": "æµ·æ²§åŒº",
	    "350206": "æ¹–é‡ŒåŒº",
	    "350211": "é›†ç¾ŽåŒº",
	    "350212": "åŒå®‰åŒº",
	    "350213": "ç¿”å®‰åŒº",
	    "350214": "å…¶å®ƒåŒº",
	    "350300": "èŽ†ç”°å¸‚",
	    "350302": "åŸŽåŽ¢åŒº",
	    "350303": "æ¶µæ±ŸåŒº",
	    "350304": "è”åŸŽåŒº",
	    "350305": "ç§€å±¿åŒº",
	    "350322": "ä»™æ¸¸åŽ¿",
	    "350323": "å…¶å®ƒåŒº",
	    "350400": "ä¸‰æ˜Žå¸‚",
	    "350402": "æ¢…åˆ—åŒº",
	    "350403": "ä¸‰å…ƒåŒº",
	    "350421": "æ˜ŽæºªåŽ¿",
	    "350423": "æ¸…æµåŽ¿",
	    "350424": "å®åŒ–åŽ¿",
	    "350425": "å¤§ç”°åŽ¿",
	    "350426": "å°¤æºªåŽ¿",
	    "350427": "æ²™åŽ¿",
	    "350428": "å°†ä¹åŽ¿",
	    "350429": "æ³°å®åŽ¿",
	    "350430": "å»ºå®åŽ¿",
	    "350481": "æ°¸å®‰å¸‚",
	    "350482": "å…¶å®ƒåŒº",
	    "350500": "æ³‰å·žå¸‚",
	    "350502": "é²¤åŸŽåŒº",
	    "350503": "ä¸°æ³½åŒº",
	    "350504": "æ´›æ±ŸåŒº",
	    "350505": "æ³‰æ¸¯åŒº",
	    "350521": "æƒ å®‰åŽ¿",
	    "350524": "å®‰æºªåŽ¿",
	    "350525": "æ°¸æ˜¥åŽ¿",
	    "350526": "å¾·åŒ–åŽ¿",
	    "350527": "é‡‘é—¨åŽ¿",
	    "350581": "çŸ³ç‹®å¸‚",
	    "350582": "æ™‹æ±Ÿå¸‚",
	    "350583": "å—å®‰å¸‚",
	    "350584": "å…¶å®ƒåŒº",
	    "350600": "æ¼³å·žå¸‚",
	    "350602": "èŠ—åŸŽåŒº",
	    "350603": "é¾™æ–‡åŒº",
	    "350622": "äº‘éœ„åŽ¿",
	    "350623": "æ¼³æµ¦åŽ¿",
	    "350624": "è¯å®‰åŽ¿",
	    "350625": "é•¿æ³°åŽ¿",
	    "350626": "ä¸œå±±åŽ¿",
	    "350627": "å—é–åŽ¿",
	    "350628": "å¹³å’ŒåŽ¿",
	    "350629": "åŽå®‰åŽ¿",
	    "350681": "é¾™æµ·å¸‚",
	    "350682": "å…¶å®ƒåŒº",
	    "350700": "å—å¹³å¸‚",
	    "350702": "å»¶å¹³åŒº",
	    "350721": "é¡ºæ˜ŒåŽ¿",
	    "350722": "æµ¦åŸŽåŽ¿",
	    "350723": "å…‰æ³½åŽ¿",
	    "350724": "æ¾æºªåŽ¿",
	    "350725": "æ”¿å’ŒåŽ¿",
	    "350781": "é‚µæ­¦å¸‚",
	    "350782": "æ­¦å¤·å±±å¸‚",
	    "350783": "å»ºç“¯å¸‚",
	    "350784": "å»ºé˜³å¸‚",
	    "350785": "å…¶å®ƒåŒº",
	    "350800": "é¾™å²©å¸‚",
	    "350802": "æ–°ç½—åŒº",
	    "350821": "é•¿æ±€åŽ¿",
	    "350822": "æ°¸å®šåŽ¿",
	    "350823": "ä¸Šæ­åŽ¿",
	    "350824": "æ­¦å¹³åŽ¿",
	    "350825": "è¿žåŸŽåŽ¿",
	    "350881": "æ¼³å¹³å¸‚",
	    "350882": "å…¶å®ƒåŒº",
	    "350900": "å®å¾·å¸‚",
	    "350902": "è•‰åŸŽåŒº",
	    "350921": "éœžæµ¦åŽ¿",
	    "350922": "å¤ç”°åŽ¿",
	    "350923": "å±å—åŽ¿",
	    "350924": "å¯¿å®åŽ¿",
	    "350925": "å‘¨å®åŽ¿",
	    "350926": "æŸ˜è£åŽ¿",
	    "350981": "ç¦å®‰å¸‚",
	    "350982": "ç¦é¼Žå¸‚",
	    "350983": "å…¶å®ƒåŒº",
	    "360000": "æ±Ÿè¥¿çœ",
	    "360100": "å—æ˜Œå¸‚",
	    "360102": "ä¸œæ¹–åŒº",
	    "360103": "è¥¿æ¹–åŒº",
	    "360104": "é’äº‘è°±åŒº",
	    "360105": "æ¹¾é‡ŒåŒº",
	    "360111": "é’å±±æ¹–åŒº",
	    "360121": "å—æ˜ŒåŽ¿",
	    "360122": "æ–°å»ºåŽ¿",
	    "360123": "å®‰ä¹‰åŽ¿",
	    "360124": "è¿›è´¤åŽ¿",
	    "360128": "å…¶å®ƒåŒº",
	    "360200": "æ™¯å¾·é•‡å¸‚",
	    "360202": "æ˜Œæ±ŸåŒº",
	    "360203": "ç å±±åŒº",
	    "360222": "æµ®æ¢åŽ¿",
	    "360281": "ä¹å¹³å¸‚",
	    "360282": "å…¶å®ƒåŒº",
	    "360300": "èä¹¡å¸‚",
	    "360302": "å®‰æºåŒº",
	    "360313": "æ¹˜ä¸œåŒº",
	    "360321": "èŽ²èŠ±åŽ¿",
	    "360322": "ä¸Šæ —åŽ¿",
	    "360323": "èŠ¦æºªåŽ¿",
	    "360324": "å…¶å®ƒåŒº",
	    "360400": "ä¹æ±Ÿå¸‚",
	    "360402": "åºå±±åŒº",
	    "360403": "æµ”é˜³åŒº",
	    "360421": "ä¹æ±ŸåŽ¿",
	    "360423": "æ­¦å®åŽ¿",
	    "360424": "ä¿®æ°´åŽ¿",
	    "360425": "æ°¸ä¿®åŽ¿",
	    "360426": "å¾·å®‰åŽ¿",
	    "360427": "æ˜Ÿå­åŽ¿",
	    "360428": "éƒ½æ˜ŒåŽ¿",
	    "360429": "æ¹–å£åŽ¿",
	    "360430": "å½­æ³½åŽ¿",
	    "360481": "ç‘žæ˜Œå¸‚",
	    "360482": "å…¶å®ƒåŒº",
	    "360483": "å…±é’åŸŽå¸‚",
	    "360500": "æ–°ä½™å¸‚",
	    "360502": "æ¸æ°´åŒº",
	    "360521": "åˆ†å®œåŽ¿",
	    "360522": "å…¶å®ƒåŒº",
	    "360600": "é¹°æ½­å¸‚",
	    "360602": "æœˆæ¹–åŒº",
	    "360622": "ä½™æ±ŸåŽ¿",
	    "360681": "è´µæºªå¸‚",
	    "360682": "å…¶å®ƒåŒº",
	    "360700": "èµ£å·žå¸‚",
	    "360702": "ç« è´¡åŒº",
	    "360721": "èµ£åŽ¿",
	    "360722": "ä¿¡ä¸°åŽ¿",
	    "360723": "å¤§ä½™åŽ¿",
	    "360724": "ä¸ŠçŠ¹åŽ¿",
	    "360725": "å´‡ä¹‰åŽ¿",
	    "360726": "å®‰è¿œåŽ¿",
	    "360727": "é¾™å—åŽ¿",
	    "360728": "å®šå—åŽ¿",
	    "360729": "å…¨å—åŽ¿",
	    "360730": "å®éƒ½åŽ¿",
	    "360731": "äºŽéƒ½åŽ¿",
	    "360732": "å…´å›½åŽ¿",
	    "360733": "ä¼šæ˜ŒåŽ¿",
	    "360734": "å¯»ä¹ŒåŽ¿",
	    "360735": "çŸ³åŸŽåŽ¿",
	    "360781": "ç‘žé‡‘å¸‚",
	    "360782": "å—åº·å¸‚",
	    "360783": "å…¶å®ƒåŒº",
	    "360800": "å‰å®‰å¸‚",
	    "360802": "å‰å·žåŒº",
	    "360803": "é’åŽŸåŒº",
	    "360821": "å‰å®‰åŽ¿",
	    "360822": "å‰æ°´åŽ¿",
	    "360823": "å³¡æ±ŸåŽ¿",
	    "360824": "æ–°å¹²åŽ¿",
	    "360825": "æ°¸ä¸°åŽ¿",
	    "360826": "æ³°å’ŒåŽ¿",
	    "360827": "é‚å·åŽ¿",
	    "360828": "ä¸‡å®‰åŽ¿",
	    "360829": "å®‰ç¦åŽ¿",
	    "360830": "æ°¸æ–°åŽ¿",
	    "360881": "äº•å†ˆå±±å¸‚",
	    "360882": "å…¶å®ƒåŒº",
	    "360900": "å®œæ˜¥å¸‚",
	    "360902": "è¢å·žåŒº",
	    "360921": "å¥‰æ–°åŽ¿",
	    "360922": "ä¸‡è½½åŽ¿",
	    "360923": "ä¸Šé«˜åŽ¿",
	    "360924": "å®œä¸°åŽ¿",
	    "360925": "é–å®‰åŽ¿",
	    "360926": "é“œé¼“åŽ¿",
	    "360981": "ä¸°åŸŽå¸‚",
	    "360982": "æ¨Ÿæ ‘å¸‚",
	    "360983": "é«˜å®‰å¸‚",
	    "360984": "å…¶å®ƒåŒº",
	    "361000": "æŠšå·žå¸‚",
	    "361002": "ä¸´å·åŒº",
	    "361021": "å—åŸŽåŽ¿",
	    "361022": "é»Žå·åŽ¿",
	    "361023": "å—ä¸°åŽ¿",
	    "361024": "å´‡ä»åŽ¿",
	    "361025": "ä¹å®‰åŽ¿",
	    "361026": "å®œé»„åŽ¿",
	    "361027": "é‡‘æºªåŽ¿",
	    "361028": "èµ„æºªåŽ¿",
	    "361029": "ä¸œä¹¡åŽ¿",
	    "361030": "å¹¿æ˜ŒåŽ¿",
	    "361031": "å…¶å®ƒåŒº",
	    "361100": "ä¸Šé¥¶å¸‚",
	    "361102": "ä¿¡å·žåŒº",
	    "361121": "ä¸Šé¥¶åŽ¿",
	    "361122": "å¹¿ä¸°åŽ¿",
	    "361123": "çŽ‰å±±åŽ¿",
	    "361124": "é“…å±±åŽ¿",
	    "361125": "æ¨ªå³°åŽ¿",
	    "361126": "å¼‹é˜³åŽ¿",
	    "361127": "ä½™å¹²åŽ¿",
	    "361128": "é„±é˜³åŽ¿",
	    "361129": "ä¸‡å¹´åŽ¿",
	    "361130": "å©ºæºåŽ¿",
	    "361181": "å¾·å…´å¸‚",
	    "361182": "å…¶å®ƒåŒº",
	    "370000": "å±±ä¸œçœ",
	    "370100": "æµŽå—å¸‚",
	    "370102": "åŽ†ä¸‹åŒº",
	    "370103": "å¸‚ä¸­åŒº",
	    "370104": "æ§è«åŒº",
	    "370105": "å¤©æ¡¥åŒº",
	    "370112": "åŽ†åŸŽåŒº",
	    "370113": "é•¿æ¸…åŒº",
	    "370124": "å¹³é˜´åŽ¿",
	    "370125": "æµŽé˜³åŽ¿",
	    "370126": "å•†æ²³åŽ¿",
	    "370181": "ç« ä¸˜å¸‚",
	    "370182": "å…¶å®ƒåŒº",
	    "370200": "é’å²›å¸‚",
	    "370202": "å¸‚å—åŒº",
	    "370203": "å¸‚åŒ—åŒº",
	    "370211": "é»„å²›åŒº",
	    "370212": "å´‚å±±åŒº",
	    "370213": "æŽæ²§åŒº",
	    "370214": "åŸŽé˜³åŒº",
	    "370281": "èƒ¶å·žå¸‚",
	    "370282": "å³å¢¨å¸‚",
	    "370283": "å¹³åº¦å¸‚",
	    "370285": "èŽ±è¥¿å¸‚",
	    "370286": "å…¶å®ƒåŒº",
	    "370300": "æ·„åšå¸‚",
	    "370302": "æ·„å·åŒº",
	    "370303": "å¼ åº—åŒº",
	    "370304": "åšå±±åŒº",
	    "370305": "ä¸´æ·„åŒº",
	    "370306": "å‘¨æ‘åŒº",
	    "370321": "æ¡“å°åŽ¿",
	    "370322": "é«˜é’åŽ¿",
	    "370323": "æ²‚æºåŽ¿",
	    "370324": "å…¶å®ƒåŒº",
	    "370400": "æž£åº„å¸‚",
	    "370402": "å¸‚ä¸­åŒº",
	    "370403": "è–›åŸŽåŒº",
	    "370404": "å³„åŸŽåŒº",
	    "370405": "å°å„¿åº„åŒº",
	    "370406": "å±±äº­åŒº",
	    "370481": "æ»•å·žå¸‚",
	    "370482": "å…¶å®ƒåŒº",
	    "370500": "ä¸œè¥å¸‚",
	    "370502": "ä¸œè¥åŒº",
	    "370503": "æ²³å£åŒº",
	    "370521": "åž¦åˆ©åŽ¿",
	    "370522": "åˆ©æ´¥åŽ¿",
	    "370523": "å¹¿é¥¶åŽ¿",
	    "370591": "å…¶å®ƒåŒº",
	    "370600": "çƒŸå°å¸‚",
	    "370602": "èŠç½˜åŒº",
	    "370611": "ç¦å±±åŒº",
	    "370612": "ç‰Ÿå¹³åŒº",
	    "370613": "èŽ±å±±åŒº",
	    "370634": "é•¿å²›åŽ¿",
	    "370681": "é¾™å£å¸‚",
	    "370682": "èŽ±é˜³å¸‚",
	    "370683": "èŽ±å·žå¸‚",
	    "370684": "è“¬èŽ±å¸‚",
	    "370685": "æ‹›è¿œå¸‚",
	    "370686": "æ –éœžå¸‚",
	    "370687": "æµ·é˜³å¸‚",
	    "370688": "å…¶å®ƒåŒº",
	    "370700": "æ½åŠå¸‚",
	    "370702": "æ½åŸŽåŒº",
	    "370703": "å¯’äº­åŒº",
	    "370704": "åŠå­åŒº",
	    "370705": "å¥Žæ–‡åŒº",
	    "370724": "ä¸´æœåŽ¿",
	    "370725": "æ˜Œä¹åŽ¿",
	    "370781": "é’å·žå¸‚",
	    "370782": "è¯¸åŸŽå¸‚",
	    "370783": "å¯¿å…‰å¸‚",
	    "370784": "å®‰ä¸˜å¸‚",
	    "370785": "é«˜å¯†å¸‚",
	    "370786": "æ˜Œé‚‘å¸‚",
	    "370787": "å…¶å®ƒåŒº",
	    "370800": "æµŽå®å¸‚",
	    "370802": "å¸‚ä¸­åŒº",
	    "370811": "ä»»åŸŽåŒº",
	    "370826": "å¾®å±±åŽ¿",
	    "370827": "é±¼å°åŽ¿",
	    "370828": "é‡‘ä¹¡åŽ¿",
	    "370829": "å˜‰ç¥¥åŽ¿",
	    "370830": "æ±¶ä¸ŠåŽ¿",
	    "370831": "æ³—æ°´åŽ¿",
	    "370832": "æ¢å±±åŽ¿",
	    "370881": "æ›²é˜œå¸‚",
	    "370882": "å…–å·žå¸‚",
	    "370883": "é‚¹åŸŽå¸‚",
	    "370884": "å…¶å®ƒåŒº",
	    "370900": "æ³°å®‰å¸‚",
	    "370902": "æ³°å±±åŒº",
	    "370903": "å²±å²³åŒº",
	    "370921": "å®é˜³åŽ¿",
	    "370923": "ä¸œå¹³åŽ¿",
	    "370982": "æ–°æ³°å¸‚",
	    "370983": "è‚¥åŸŽå¸‚",
	    "370984": "å…¶å®ƒåŒº",
	    "371000": "å¨æµ·å¸‚",
	    "371002": "çŽ¯ç¿ åŒº",
	    "371081": "æ–‡ç™»å¸‚",
	    "371082": "è£æˆå¸‚",
	    "371083": "ä¹³å±±å¸‚",
	    "371084": "å…¶å®ƒåŒº",
	    "371100": "æ—¥ç…§å¸‚",
	    "371102": "ä¸œæ¸¯åŒº",
	    "371103": "å²šå±±åŒº",
	    "371121": "äº”èŽ²åŽ¿",
	    "371122": "èŽ’åŽ¿",
	    "371123": "å…¶å®ƒåŒº",
	    "371200": "èŽ±èŠœå¸‚",
	    "371202": "èŽ±åŸŽåŒº",
	    "371203": "é’¢åŸŽåŒº",
	    "371204": "å…¶å®ƒåŒº",
	    "371300": "ä¸´æ²‚å¸‚",
	    "371302": "å…°å±±åŒº",
	    "371311": "ç½—åº„åŒº",
	    "371312": "æ²³ä¸œåŒº",
	    "371321": "æ²‚å—åŽ¿",
	    "371322": "éƒ¯åŸŽåŽ¿",
	    "371323": "æ²‚æ°´åŽ¿",
	    "371324": "è‹å±±åŽ¿",
	    "371325": "è´¹åŽ¿",
	    "371326": "å¹³é‚‘åŽ¿",
	    "371327": "èŽ’å—åŽ¿",
	    "371328": "è’™é˜´åŽ¿",
	    "371329": "ä¸´æ²­åŽ¿",
	    "371330": "å…¶å®ƒåŒº",
	    "371400": "å¾·å·žå¸‚",
	    "371402": "å¾·åŸŽåŒº",
	    "371421": "é™µåŽ¿",
	    "371422": "å®æ´¥åŽ¿",
	    "371423": "åº†äº‘åŽ¿",
	    "371424": "ä¸´é‚‘åŽ¿",
	    "371425": "é½æ²³åŽ¿",
	    "371426": "å¹³åŽŸåŽ¿",
	    "371427": "å¤æ´¥åŽ¿",
	    "371428": "æ­¦åŸŽåŽ¿",
	    "371481": "ä¹é™µå¸‚",
	    "371482": "ç¦¹åŸŽå¸‚",
	    "371483": "å…¶å®ƒåŒº",
	    "371500": "èŠåŸŽå¸‚",
	    "371502": "ä¸œæ˜ŒåºœåŒº",
	    "371521": "é˜³è°·åŽ¿",
	    "371522": "èŽ˜åŽ¿",
	    "371523": "èŒŒå¹³åŽ¿",
	    "371524": "ä¸œé˜¿åŽ¿",
	    "371525": "å† åŽ¿",
	    "371526": "é«˜å”åŽ¿",
	    "371581": "ä¸´æ¸…å¸‚",
	    "371582": "å…¶å®ƒåŒº",
	    "371600": "æ»¨å·žå¸‚",
	    "371602": "æ»¨åŸŽåŒº",
	    "371621": "æƒ æ°‘åŽ¿",
	    "371622": "é˜³ä¿¡åŽ¿",
	    "371623": "æ— æ££åŽ¿",
	    "371624": "æ²¾åŒ–åŽ¿",
	    "371625": "åšå…´åŽ¿",
	    "371626": "é‚¹å¹³åŽ¿",
	    "371627": "å…¶å®ƒåŒº",
	    "371700": "èæ³½å¸‚",
	    "371702": "ç‰¡ä¸¹åŒº",
	    "371721": "æ›¹åŽ¿",
	    "371722": "å•åŽ¿",
	    "371723": "æˆæ­¦åŽ¿",
	    "371724": "å·¨é‡ŽåŽ¿",
	    "371725": "éƒ“åŸŽåŽ¿",
	    "371726": "é„„åŸŽåŽ¿",
	    "371727": "å®šé™¶åŽ¿",
	    "371728": "ä¸œæ˜ŽåŽ¿",
	    "371729": "å…¶å®ƒåŒº",
	    "410000": "æ²³å—çœ",
	    "410100": "éƒ‘å·žå¸‚",
	    "410102": "ä¸­åŽŸåŒº",
	    "410103": "äºŒä¸ƒåŒº",
	    "410104": "ç®¡åŸŽå›žæ—åŒº",
	    "410105": "é‡‘æ°´åŒº",
	    "410106": "ä¸Šè¡—åŒº",
	    "410108": "æƒ æµŽåŒº",
	    "410122": "ä¸­ç‰ŸåŽ¿",
	    "410181": "å·©ä¹‰å¸‚",
	    "410182": "è¥é˜³å¸‚",
	    "410183": "æ–°å¯†å¸‚",
	    "410184": "æ–°éƒ‘å¸‚",
	    "410185": "ç™»å°å¸‚",
	    "410188": "å…¶å®ƒåŒº",
	    "410200": "å¼€å°å¸‚",
	    "410202": "é¾™äº­åŒº",
	    "410203": "é¡ºæ²³å›žæ—åŒº",
	    "410204": "é¼“æ¥¼åŒº",
	    "410205": "ç¦¹çŽ‹å°åŒº",
	    "410211": "é‡‘æ˜ŽåŒº",
	    "410221": "æžåŽ¿",
	    "410222": "é€šè®¸åŽ¿",
	    "410223": "å°‰æ°åŽ¿",
	    "410224": "å¼€å°åŽ¿",
	    "410225": "å…°è€ƒåŽ¿",
	    "410226": "å…¶å®ƒåŒº",
	    "410300": "æ´›é˜³å¸‚",
	    "410302": "è€åŸŽåŒº",
	    "410303": "è¥¿å·¥åŒº",
	    "410304": "ç€æ²³å›žæ—åŒº",
	    "410305": "æ¶§è¥¿åŒº",
	    "410306": "å‰åˆ©åŒº",
	    "410307": "æ´›é¾™åŒº",
	    "410322": "å­Ÿæ´¥åŽ¿",
	    "410323": "æ–°å®‰åŽ¿",
	    "410324": "æ ¾å·åŽ¿",
	    "410325": "åµ©åŽ¿",
	    "410326": "æ±é˜³åŽ¿",
	    "410327": "å®œé˜³åŽ¿",
	    "410328": "æ´›å®åŽ¿",
	    "410329": "ä¼Šå·åŽ¿",
	    "410381": "åƒå¸ˆå¸‚",
	    "410400": "å¹³é¡¶å±±å¸‚",
	    "410402": "æ–°åŽåŒº",
	    "410403": "å«ä¸œåŒº",
	    "410404": "çŸ³é¾™åŒº",
	    "410411": "æ¹›æ²³åŒº",
	    "410421": "å®ä¸°åŽ¿",
	    "410422": "å¶åŽ¿",
	    "410423": "é²å±±åŽ¿",
	    "410425": "éƒåŽ¿",
	    "410481": "èˆžé’¢å¸‚",
	    "410482": "æ±å·žå¸‚",
	    "410483": "å…¶å®ƒåŒº",
	    "410500": "å®‰é˜³å¸‚",
	    "410502": "æ–‡å³°åŒº",
	    "410503": "åŒ—å…³åŒº",
	    "410505": "æ®·éƒ½åŒº",
	    "410506": "é¾™å®‰åŒº",
	    "410522": "å®‰é˜³åŽ¿",
	    "410523": "æ±¤é˜´åŽ¿",
	    "410526": "æ»‘åŽ¿",
	    "410527": "å†…é»„åŽ¿",
	    "410581": "æž—å·žå¸‚",
	    "410582": "å…¶å®ƒåŒº",
	    "410600": "é¹¤å£å¸‚",
	    "410602": "é¹¤å±±åŒº",
	    "410603": "å±±åŸŽåŒº",
	    "410611": "æ·‡æ»¨åŒº",
	    "410621": "æµšåŽ¿",
	    "410622": "æ·‡åŽ¿",
	    "410623": "å…¶å®ƒåŒº",
	    "410700": "æ–°ä¹¡å¸‚",
	    "410702": "çº¢æ——åŒº",
	    "410703": "å«æ»¨åŒº",
	    "410704": "å‡¤æ³‰åŒº",
	    "410711": "ç‰§é‡ŽåŒº",
	    "410721": "æ–°ä¹¡åŽ¿",
	    "410724": "èŽ·å˜‰åŽ¿",
	    "410725": "åŽŸé˜³åŽ¿",
	    "410726": "å»¶æ´¥åŽ¿",
	    "410727": "å°ä¸˜åŽ¿",
	    "410728": "é•¿åž£åŽ¿",
	    "410781": "å«è¾‰å¸‚",
	    "410782": "è¾‰åŽ¿å¸‚",
	    "410783": "å…¶å®ƒåŒº",
	    "410800": "ç„¦ä½œå¸‚",
	    "410802": "è§£æ”¾åŒº",
	    "410803": "ä¸­ç«™åŒº",
	    "410804": "é©¬æ‘åŒº",
	    "410811": "å±±é˜³åŒº",
	    "410821": "ä¿®æ­¦åŽ¿",
	    "410822": "åšçˆ±åŽ¿",
	    "410823": "æ­¦é™ŸåŽ¿",
	    "410825": "æ¸©åŽ¿",
	    "410881": "æµŽæºå¸‚",
	    "410882": "æ²é˜³å¸‚",
	    "410883": "å­Ÿå·žå¸‚",
	    "410884": "å…¶å®ƒåŒº",
	    "410900": "æ¿®é˜³å¸‚",
	    "410902": "åŽé¾™åŒº",
	    "410922": "æ¸…ä¸°åŽ¿",
	    "410923": "å—ä¹åŽ¿",
	    "410926": "èŒƒåŽ¿",
	    "410927": "å°å‰åŽ¿",
	    "410928": "æ¿®é˜³åŽ¿",
	    "410929": "å…¶å®ƒåŒº",
	    "411000": "è®¸æ˜Œå¸‚",
	    "411002": "é­éƒ½åŒº",
	    "411023": "è®¸æ˜ŒåŽ¿",
	    "411024": "é„¢é™µåŽ¿",
	    "411025": "è¥„åŸŽåŽ¿",
	    "411081": "ç¦¹å·žå¸‚",
	    "411082": "é•¿è‘›å¸‚",
	    "411083": "å…¶å®ƒåŒº",
	    "411100": "æ¼¯æ²³å¸‚",
	    "411102": "æºæ±‡åŒº",
	    "411103": "éƒ¾åŸŽåŒº",
	    "411104": "å¬é™µåŒº",
	    "411121": "èˆžé˜³åŽ¿",
	    "411122": "ä¸´é¢åŽ¿",
	    "411123": "å…¶å®ƒåŒº",
	    "411200": "ä¸‰é—¨å³¡å¸‚",
	    "411202": "æ¹–æ»¨åŒº",
	    "411221": "æ¸‘æ± åŽ¿",
	    "411222": "é™•åŽ¿",
	    "411224": "å¢æ°åŽ¿",
	    "411281": "ä¹‰é©¬å¸‚",
	    "411282": "çµå®å¸‚",
	    "411283": "å…¶å®ƒåŒº",
	    "411300": "å—é˜³å¸‚",
	    "411302": "å®›åŸŽåŒº",
	    "411303": "å§é¾™åŒº",
	    "411321": "å—å¬åŽ¿",
	    "411322": "æ–¹åŸŽåŽ¿",
	    "411323": "è¥¿å³¡åŽ¿",
	    "411324": "é•‡å¹³åŽ¿",
	    "411325": "å†…ä¹¡åŽ¿",
	    "411326": "æ·…å·åŽ¿",
	    "411327": "ç¤¾æ——åŽ¿",
	    "411328": "å”æ²³åŽ¿",
	    "411329": "æ–°é‡ŽåŽ¿",
	    "411330": "æ¡æŸåŽ¿",
	    "411381": "é‚“å·žå¸‚",
	    "411382": "å…¶å®ƒåŒº",
	    "411400": "å•†ä¸˜å¸‚",
	    "411402": "æ¢å›­åŒº",
	    "411403": "ç¢é˜³åŒº",
	    "411421": "æ°‘æƒåŽ¿",
	    "411422": "ç¢åŽ¿",
	    "411423": "å®é™µåŽ¿",
	    "411424": "æŸ˜åŸŽåŽ¿",
	    "411425": "è™žåŸŽåŽ¿",
	    "411426": "å¤é‚‘åŽ¿",
	    "411481": "æ°¸åŸŽå¸‚",
	    "411482": "å…¶å®ƒåŒº",
	    "411500": "ä¿¡é˜³å¸‚",
	    "411502": "æµ‰æ²³åŒº",
	    "411503": "å¹³æ¡¥åŒº",
	    "411521": "ç½—å±±åŽ¿",
	    "411522": "å…‰å±±åŽ¿",
	    "411523": "æ–°åŽ¿",
	    "411524": "å•†åŸŽåŽ¿",
	    "411525": "å›ºå§‹åŽ¿",
	    "411526": "æ½¢å·åŽ¿",
	    "411527": "æ·®æ»¨åŽ¿",
	    "411528": "æ¯åŽ¿",
	    "411529": "å…¶å®ƒåŒº",
	    "411600": "å‘¨å£å¸‚",
	    "411602": "å·æ±‡åŒº",
	    "411621": "æ‰¶æ²ŸåŽ¿",
	    "411622": "è¥¿åŽåŽ¿",
	    "411623": "å•†æ°´åŽ¿",
	    "411624": "æ²ˆä¸˜åŽ¿",
	    "411625": "éƒ¸åŸŽåŽ¿",
	    "411626": "æ·®é˜³åŽ¿",
	    "411627": "å¤ªåº·åŽ¿",
	    "411628": "é¹¿é‚‘åŽ¿",
	    "411681": "é¡¹åŸŽå¸‚",
	    "411682": "å…¶å®ƒåŒº",
	    "411700": "é©»é©¬åº—å¸‚",
	    "411702": "é©¿åŸŽåŒº",
	    "411721": "è¥¿å¹³åŽ¿",
	    "411722": "ä¸Šè”¡åŽ¿",
	    "411723": "å¹³èˆ†åŽ¿",
	    "411724": "æ­£é˜³åŽ¿",
	    "411725": "ç¡®å±±åŽ¿",
	    "411726": "æ³Œé˜³åŽ¿",
	    "411727": "æ±å—åŽ¿",
	    "411728": "é‚å¹³åŽ¿",
	    "411729": "æ–°è”¡åŽ¿",
	    "411730": "å…¶å®ƒåŒº",
	    "420000": "æ¹–åŒ—çœ",
	    "420100": "æ­¦æ±‰å¸‚",
	    "420102": "æ±Ÿå²¸åŒº",
	    "420103": "æ±Ÿæ±‰åŒº",
	    "420104": "ç¡šå£åŒº",
	    "420105": "æ±‰é˜³åŒº",
	    "420106": "æ­¦æ˜ŒåŒº",
	    "420107": "é’å±±åŒº",
	    "420111": "æ´ªå±±åŒº",
	    "420112": "ä¸œè¥¿æ¹–åŒº",
	    "420113": "æ±‰å—åŒº",
	    "420114": "è”¡ç”¸åŒº",
	    "420115": "æ±Ÿå¤åŒº",
	    "420116": "é»„é™‚åŒº",
	    "420117": "æ–°æ´²åŒº",
	    "420118": "å…¶å®ƒåŒº",
	    "420200": "é»„çŸ³å¸‚",
	    "420202": "é»„çŸ³æ¸¯åŒº",
	    "420203": "è¥¿å¡žå±±åŒº",
	    "420204": "ä¸‹é™†åŒº",
	    "420205": "é“å±±åŒº",
	    "420222": "é˜³æ–°åŽ¿",
	    "420281": "å¤§å†¶å¸‚",
	    "420282": "å…¶å®ƒåŒº",
	    "420300": "åå °å¸‚",
	    "420302": "èŒ…ç®­åŒº",
	    "420303": "å¼ æ¹¾åŒº",
	    "420321": "éƒ§åŽ¿",
	    "420322": "éƒ§è¥¿åŽ¿",
	    "420323": "ç«¹å±±åŽ¿",
	    "420324": "ç«¹æºªåŽ¿",
	    "420325": "æˆ¿åŽ¿",
	    "420381": "ä¸¹æ±Ÿå£å¸‚",
	    "420383": "å…¶å®ƒåŒº",
	    "420500": "å®œæ˜Œå¸‚",
	    "420502": "è¥¿é™µåŒº",
	    "420503": "ä¼å®¶å²—åŒº",
	    "420504": "ç‚¹å†›åŒº",
	    "420505": "çŒ‡äº­åŒº",
	    "420506": "å¤·é™µåŒº",
	    "420525": "è¿œå®‰åŽ¿",
	    "420526": "å…´å±±åŽ¿",
	    "420527": "ç§­å½’åŽ¿",
	    "420528": "é•¿é˜³åœŸå®¶æ—è‡ªæ²»åŽ¿",
	    "420529": "äº”å³°åœŸå®¶æ—è‡ªæ²»åŽ¿",
	    "420581": "å®œéƒ½å¸‚",
	    "420582": "å½“é˜³å¸‚",
	    "420583": "æžæ±Ÿå¸‚",
	    "420584": "å…¶å®ƒåŒº",
	    "420600": "è¥„é˜³å¸‚",
	    "420602": "è¥„åŸŽåŒº",
	    "420606": "æ¨ŠåŸŽåŒº",
	    "420607": "è¥„å·žåŒº",
	    "420624": "å—æ¼³åŽ¿",
	    "420625": "è°·åŸŽåŽ¿",
	    "420626": "ä¿åº·åŽ¿",
	    "420682": "è€æ²³å£å¸‚",
	    "420683": "æž£é˜³å¸‚",
	    "420684": "å®œåŸŽå¸‚",
	    "420685": "å…¶å®ƒåŒº",
	    "420700": "é„‚å·žå¸‚",
	    "420702": "æ¢å­æ¹–åŒº",
	    "420703": "åŽå®¹åŒº",
	    "420704": "é„‚åŸŽåŒº",
	    "420705": "å…¶å®ƒåŒº",
	    "420800": "è†é—¨å¸‚",
	    "420802": "ä¸œå®åŒº",
	    "420804": "æŽ‡åˆ€åŒº",
	    "420821": "äº¬å±±åŽ¿",
	    "420822": "æ²™æ´‹åŽ¿",
	    "420881": "é’Ÿç¥¥å¸‚",
	    "420882": "å…¶å®ƒåŒº",
	    "420900": "å­æ„Ÿå¸‚",
	    "420902": "å­å—åŒº",
	    "420921": "å­æ˜ŒåŽ¿",
	    "420922": "å¤§æ‚ŸåŽ¿",
	    "420923": "äº‘æ¢¦åŽ¿",
	    "420981": "åº”åŸŽå¸‚",
	    "420982": "å®‰é™†å¸‚",
	    "420984": "æ±‰å·å¸‚",
	    "420985": "å…¶å®ƒåŒº",
	    "421000": "è†å·žå¸‚",
	    "421002": "æ²™å¸‚åŒº",
	    "421003": "è†å·žåŒº",
	    "421022": "å…¬å®‰åŽ¿",
	    "421023": "ç›‘åˆ©åŽ¿",
	    "421024": "æ±Ÿé™µåŽ¿",
	    "421081": "çŸ³é¦–å¸‚",
	    "421083": "æ´ªæ¹–å¸‚",
	    "421087": "æ¾æ»‹å¸‚",
	    "421088": "å…¶å®ƒåŒº",
	    "421100": "é»„å†ˆå¸‚",
	    "421102": "é»„å·žåŒº",
	    "421121": "å›¢é£ŽåŽ¿",
	    "421122": "çº¢å®‰åŽ¿",
	    "421123": "ç½—ç”°åŽ¿",
	    "421124": "è‹±å±±åŽ¿",
	    "421125": "æµ æ°´åŽ¿",
	    "421126": "è•²æ˜¥åŽ¿",
	    "421127": "é»„æ¢…åŽ¿",
	    "421181": "éº»åŸŽå¸‚",
	    "421182": "æ­¦ç©´å¸‚",
	    "421183": "å…¶å®ƒåŒº",
	    "421200": "å’¸å®å¸‚",
	    "421202": "å’¸å®‰åŒº",
	    "421221": "å˜‰é±¼åŽ¿",
	    "421222": "é€šåŸŽåŽ¿",
	    "421223": "å´‡é˜³åŽ¿",
	    "421224": "é€šå±±åŽ¿",
	    "421281": "èµ¤å£å¸‚",
	    "421283": "å…¶å®ƒåŒº",
	    "421300": "éšå·žå¸‚",
	    "421302": "æ›¾éƒ½åŒº",
	    "421321": "éšåŽ¿",
	    "421381": "å¹¿æ°´å¸‚",
	    "421382": "å…¶å®ƒåŒº",
	    "422800": "æ©æ–½åœŸå®¶æ—è‹—æ—è‡ªæ²»å·ž",
	    "422801": "æ©æ–½å¸‚",
	    "422802": "åˆ©å·å¸‚",
	    "422822": "å»ºå§‹åŽ¿",
	    "422823": "å·´ä¸œåŽ¿",
	    "422825": "å®£æ©åŽ¿",
	    "422826": "å’¸ä¸°åŽ¿",
	    "422827": "æ¥å‡¤åŽ¿",
	    "422828": "é¹¤å³°åŽ¿",
	    "422829": "å…¶å®ƒåŒº",
	    "429004": "ä»™æ¡ƒå¸‚",
	    "429005": "æ½œæ±Ÿå¸‚",
	    "429006": "å¤©é—¨å¸‚",
	    "429021": "ç¥žå†œæž¶æž—åŒº",
	    "430000": "æ¹–å—çœ",
	    "430100": "é•¿æ²™å¸‚",
	    "430102": "èŠ™è“‰åŒº",
	    "430103": "å¤©å¿ƒåŒº",
	    "430104": "å²³éº“åŒº",
	    "430105": "å¼€ç¦åŒº",
	    "430111": "é›¨èŠ±åŒº",
	    "430121": "é•¿æ²™åŽ¿",
	    "430122": "æœ›åŸŽåŒº",
	    "430124": "å®ä¹¡åŽ¿",
	    "430181": "æµé˜³å¸‚",
	    "430182": "å…¶å®ƒåŒº",
	    "430200": "æ ªæ´²å¸‚",
	    "430202": "è·å¡˜åŒº",
	    "430203": "èŠ¦æ·žåŒº",
	    "430204": "çŸ³å³°åŒº",
	    "430211": "å¤©å…ƒåŒº",
	    "430221": "æ ªæ´²åŽ¿",
	    "430223": "æ”¸åŽ¿",
	    "430224": "èŒ¶é™µåŽ¿",
	    "430225": "ç‚Žé™µåŽ¿",
	    "430281": "é†´é™µå¸‚",
	    "430282": "å…¶å®ƒåŒº",
	    "430300": "æ¹˜æ½­å¸‚",
	    "430302": "é›¨æ¹–åŒº",
	    "430304": "å²³å¡˜åŒº",
	    "430321": "æ¹˜æ½­åŽ¿",
	    "430381": "æ¹˜ä¹¡å¸‚",
	    "430382": "éŸ¶å±±å¸‚",
	    "430383": "å…¶å®ƒåŒº",
	    "430400": "è¡¡é˜³å¸‚",
	    "430405": "ç æ™–åŒº",
	    "430406": "é›å³°åŒº",
	    "430407": "çŸ³é¼“åŒº",
	    "430408": "è’¸æ¹˜åŒº",
	    "430412": "å—å²³åŒº",
	    "430421": "è¡¡é˜³åŽ¿",
	    "430422": "è¡¡å—åŽ¿",
	    "430423": "è¡¡å±±åŽ¿",
	    "430424": "è¡¡ä¸œåŽ¿",
	    "430426": "ç¥ä¸œåŽ¿",
	    "430481": "è€’é˜³å¸‚",
	    "430482": "å¸¸å®å¸‚",
	    "430483": "å…¶å®ƒåŒº",
	    "430500": "é‚µé˜³å¸‚",
	    "430502": "åŒæ¸…åŒº",
	    "430503": "å¤§ç¥¥åŒº",
	    "430511": "åŒ—å¡”åŒº",
	    "430521": "é‚µä¸œåŽ¿",
	    "430522": "æ–°é‚µåŽ¿",
	    "430523": "é‚µé˜³åŽ¿",
	    "430524": "éš†å›žåŽ¿",
	    "430525": "æ´žå£åŽ¿",
	    "430527": "ç»¥å®åŽ¿",
	    "430528": "æ–°å®åŽ¿",
	    "430529": "åŸŽæ­¥è‹—æ—è‡ªæ²»åŽ¿",
	    "430581": "æ­¦å†ˆå¸‚",
	    "430582": "å…¶å®ƒåŒº",
	    "430600": "å²³é˜³å¸‚",
	    "430602": "å²³é˜³æ¥¼åŒº",
	    "430603": "äº‘æºªåŒº",
	    "430611": "å›å±±åŒº",
	    "430621": "å²³é˜³åŽ¿",
	    "430623": "åŽå®¹åŽ¿",
	    "430624": "æ¹˜é˜´åŽ¿",
	    "430626": "å¹³æ±ŸåŽ¿",
	    "430681": "æ±¨ç½—å¸‚",
	    "430682": "ä¸´æ¹˜å¸‚",
	    "430683": "å…¶å®ƒåŒº",
	    "430700": "å¸¸å¾·å¸‚",
	    "430702": "æ­¦é™µåŒº",
	    "430703": "é¼ŽåŸŽåŒº",
	    "430721": "å®‰ä¹¡åŽ¿",
	    "430722": "æ±‰å¯¿åŽ¿",
	    "430723": "æ¾§åŽ¿",
	    "430724": "ä¸´æ¾§åŽ¿",
	    "430725": "æ¡ƒæºåŽ¿",
	    "430726": "çŸ³é—¨åŽ¿",
	    "430781": "æ´¥å¸‚å¸‚",
	    "430782": "å…¶å®ƒåŒº",
	    "430800": "å¼ å®¶ç•Œå¸‚",
	    "430802": "æ°¸å®šåŒº",
	    "430811": "æ­¦é™µæºåŒº",
	    "430821": "æ…ˆåˆ©åŽ¿",
	    "430822": "æ¡‘æ¤åŽ¿",
	    "430823": "å…¶å®ƒåŒº",
	    "430900": "ç›Šé˜³å¸‚",
	    "430902": "èµ„é˜³åŒº",
	    "430903": "èµ«å±±åŒº",
	    "430921": "å—åŽ¿",
	    "430922": "æ¡ƒæ±ŸåŽ¿",
	    "430923": "å®‰åŒ–åŽ¿",
	    "430981": "æ²…æ±Ÿå¸‚",
	    "430982": "å…¶å®ƒåŒº",
	    "431000": "éƒ´å·žå¸‚",
	    "431002": "åŒ—æ¹–åŒº",
	    "431003": "è‹ä»™åŒº",
	    "431021": "æ¡‚é˜³åŽ¿",
	    "431022": "å®œç« åŽ¿",
	    "431023": "æ°¸å…´åŽ¿",
	    "431024": "å˜‰ç¦¾åŽ¿",
	    "431025": "ä¸´æ­¦åŽ¿",
	    "431026": "æ±åŸŽåŽ¿",
	    "431027": "æ¡‚ä¸œåŽ¿",
	    "431028": "å®‰ä»åŽ¿",
	    "431081": "èµ„å…´å¸‚",
	    "431082": "å…¶å®ƒåŒº",
	    "431100": "æ°¸å·žå¸‚",
	    "431102": "é›¶é™µåŒº",
	    "431103": "å†·æ°´æ»©åŒº",
	    "431121": "ç¥é˜³åŽ¿",
	    "431122": "ä¸œå®‰åŽ¿",
	    "431123": "åŒç‰ŒåŽ¿",
	    "431124": "é“åŽ¿",
	    "431125": "æ±Ÿæ°¸åŽ¿",
	    "431126": "å®è¿œåŽ¿",
	    "431127": "è“å±±åŽ¿",
	    "431128": "æ–°ç”°åŽ¿",
	    "431129": "æ±ŸåŽç‘¶æ—è‡ªæ²»åŽ¿",
	    "431130": "å…¶å®ƒåŒº",
	    "431200": "æ€€åŒ–å¸‚",
	    "431202": "é¹¤åŸŽåŒº",
	    "431221": "ä¸­æ–¹åŽ¿",
	    "431222": "æ²…é™µåŽ¿",
	    "431223": "è¾°æºªåŽ¿",
	    "431224": "æº†æµ¦åŽ¿",
	    "431225": "ä¼šåŒåŽ¿",
	    "431226": "éº»é˜³è‹—æ—è‡ªæ²»åŽ¿",
	    "431227": "æ–°æ™ƒä¾—æ—è‡ªæ²»åŽ¿",
	    "431228": "èŠ·æ±Ÿä¾—æ—è‡ªæ²»åŽ¿",
	    "431229": "é–å·žè‹—æ—ä¾—æ—è‡ªæ²»åŽ¿",
	    "431230": "é€šé“ä¾—æ—è‡ªæ²»åŽ¿",
	    "431281": "æ´ªæ±Ÿå¸‚",
	    "431282": "å…¶å®ƒåŒº",
	    "431300": "å¨„åº•å¸‚",
	    "431302": "å¨„æ˜ŸåŒº",
	    "431321": "åŒå³°åŽ¿",
	    "431322": "æ–°åŒ–åŽ¿",
	    "431381": "å†·æ°´æ±Ÿå¸‚",
	    "431382": "æ¶Ÿæºå¸‚",
	    "431383": "å…¶å®ƒåŒº",
	    "433100": "æ¹˜è¥¿åœŸå®¶æ—è‹—æ—è‡ªæ²»å·ž",
	    "433101": "å‰é¦–å¸‚",
	    "433122": "æ³¸æºªåŽ¿",
	    "433123": "å‡¤å‡°åŽ¿",
	    "433124": "èŠ±åž£åŽ¿",
	    "433125": "ä¿é–åŽ¿",
	    "433126": "å¤ä¸ˆåŽ¿",
	    "433127": "æ°¸é¡ºåŽ¿",
	    "433130": "é¾™å±±åŽ¿",
	    "433131": "å…¶å®ƒåŒº",
	    "440000": "å¹¿ä¸œçœ",
	    "440100": "å¹¿å·žå¸‚",
	    "440103": "è”æ¹¾åŒº",
	    "440104": "è¶Šç§€åŒº",
	    "440105": "æµ·ç åŒº",
	    "440106": "å¤©æ²³åŒº",
	    "440111": "ç™½äº‘åŒº",
	    "440112": "é»„åŸ”åŒº",
	    "440113": "ç•ªç¦ºåŒº",
	    "440114": "èŠ±éƒ½åŒº",
	    "440115": "å—æ²™åŒº",
	    "440116": "èå²—åŒº",
	    "440183": "å¢žåŸŽå¸‚",
	    "440184": "ä»ŽåŒ–å¸‚",
	    "440189": "å…¶å®ƒåŒº",
	    "440200": "éŸ¶å…³å¸‚",
	    "440203": "æ­¦æ±ŸåŒº",
	    "440204": "æµˆæ±ŸåŒº",
	    "440205": "æ›²æ±ŸåŒº",
	    "440222": "å§‹å…´åŽ¿",
	    "440224": "ä»åŒ–åŽ¿",
	    "440229": "ç¿æºåŽ¿",
	    "440232": "ä¹³æºç‘¶æ—è‡ªæ²»åŽ¿",
	    "440233": "æ–°ä¸°åŽ¿",
	    "440281": "ä¹æ˜Œå¸‚",
	    "440282": "å—é›„å¸‚",
	    "440283": "å…¶å®ƒåŒº",
	    "440300": "æ·±åœ³å¸‚",
	    "440303": "ç½—æ¹–åŒº",
	    "440304": "ç¦ç”°åŒº",
	    "440305": "å—å±±åŒº",
	    "440306": "å®å®‰åŒº",
	    "440307": "é¾™å²—åŒº",
	    "440308": "ç›ç”°åŒº",
	    "440309": "å…¶å®ƒåŒº",
	    "440320": "å…‰æ˜Žæ–°åŒº",
	    "440321": "åªå±±æ–°åŒº",
	    "440322": "å¤§é¹æ–°åŒº",
	    "440323": "é¾™åŽæ–°åŒº",
	    "440400": "ç æµ·å¸‚",
	    "440402": "é¦™æ´²åŒº",
	    "440403": "æ–—é—¨åŒº",
	    "440404": "é‡‘æ¹¾åŒº",
	    "440488": "å…¶å®ƒåŒº",
	    "440500": "æ±•å¤´å¸‚",
	    "440507": "é¾™æ¹–åŒº",
	    "440511": "é‡‘å¹³åŒº",
	    "440512": "æ¿ æ±ŸåŒº",
	    "440513": "æ½®é˜³åŒº",
	    "440514": "æ½®å—åŒº",
	    "440515": "æ¾„æµ·åŒº",
	    "440523": "å—æ¾³åŽ¿",
	    "440524": "å…¶å®ƒåŒº",
	    "440600": "ä½›å±±å¸‚",
	    "440604": "ç¦…åŸŽåŒº",
	    "440605": "å—æµ·åŒº",
	    "440606": "é¡ºå¾·åŒº",
	    "440607": "ä¸‰æ°´åŒº",
	    "440608": "é«˜æ˜ŽåŒº",
	    "440609": "å…¶å®ƒåŒº",
	    "440700": "æ±Ÿé—¨å¸‚",
	    "440703": "è“¬æ±ŸåŒº",
	    "440704": "æ±Ÿæµ·åŒº",
	    "440705": "æ–°ä¼šåŒº",
	    "440781": "å°å±±å¸‚",
	    "440783": "å¼€å¹³å¸‚",
	    "440784": "é¹¤å±±å¸‚",
	    "440785": "æ©å¹³å¸‚",
	    "440786": "å…¶å®ƒåŒº",
	    "440800": "æ¹›æ±Ÿå¸‚",
	    "440802": "èµ¤åŽåŒº",
	    "440803": "éœžå±±åŒº",
	    "440804": "å¡å¤´åŒº",
	    "440811": "éº»ç« åŒº",
	    "440823": "é‚æºªåŽ¿",
	    "440825": "å¾é—»åŽ¿",
	    "440881": "å»‰æ±Ÿå¸‚",
	    "440882": "é›·å·žå¸‚",
	    "440883": "å´å·å¸‚",
	    "440884": "å…¶å®ƒåŒº",
	    "440900": "èŒ‚åå¸‚",
	    "440902": "èŒ‚å—åŒº",
	    "440903": "èŒ‚æ¸¯åŒº",
	    "440923": "ç”µç™½åŽ¿",
	    "440981": "é«˜å·žå¸‚",
	    "440982": "åŒ–å·žå¸‚",
	    "440983": "ä¿¡å®œå¸‚",
	    "440984": "å…¶å®ƒåŒº",
	    "441200": "è‚‡åº†å¸‚",
	    "441202": "ç«¯å·žåŒº",
	    "441203": "é¼Žæ¹–åŒº",
	    "441223": "å¹¿å®åŽ¿",
	    "441224": "æ€€é›†åŽ¿",
	    "441225": "å°å¼€åŽ¿",
	    "441226": "å¾·åº†åŽ¿",
	    "441283": "é«˜è¦å¸‚",
	    "441284": "å››ä¼šå¸‚",
	    "441285": "å…¶å®ƒåŒº",
	    "441300": "æƒ å·žå¸‚",
	    "441302": "æƒ åŸŽåŒº",
	    "441303": "æƒ é˜³åŒº",
	    "441322": "åšç½—åŽ¿",
	    "441323": "æƒ ä¸œåŽ¿",
	    "441324": "é¾™é—¨åŽ¿",
	    "441325": "å…¶å®ƒåŒº",
	    "441400": "æ¢…å·žå¸‚",
	    "441402": "æ¢…æ±ŸåŒº",
	    "441421": "æ¢…åŽ¿",
	    "441422": "å¤§åŸ”åŽ¿",
	    "441423": "ä¸°é¡ºåŽ¿",
	    "441424": "äº”åŽåŽ¿",
	    "441426": "å¹³è¿œåŽ¿",
	    "441427": "è•‰å²­åŽ¿",
	    "441481": "å…´å®å¸‚",
	    "441482": "å…¶å®ƒåŒº",
	    "441500": "æ±•å°¾å¸‚",
	    "441502": "åŸŽåŒº",
	    "441521": "æµ·ä¸°åŽ¿",
	    "441523": "é™†æ²³åŽ¿",
	    "441581": "é™†ä¸°å¸‚",
	    "441582": "å…¶å®ƒåŒº",
	    "441600": "æ²³æºå¸‚",
	    "441602": "æºåŸŽåŒº",
	    "441621": "ç´«é‡‘åŽ¿",
	    "441622": "é¾™å·åŽ¿",
	    "441623": "è¿žå¹³åŽ¿",
	    "441624": "å’Œå¹³åŽ¿",
	    "441625": "ä¸œæºåŽ¿",
	    "441626": "å…¶å®ƒåŒº",
	    "441700": "é˜³æ±Ÿå¸‚",
	    "441702": "æ±ŸåŸŽåŒº",
	    "441721": "é˜³è¥¿åŽ¿",
	    "441723": "é˜³ä¸œåŽ¿",
	    "441781": "é˜³æ˜¥å¸‚",
	    "441782": "å…¶å®ƒåŒº",
	    "441800": "æ¸…è¿œå¸‚",
	    "441802": "æ¸…åŸŽåŒº",
	    "441821": "ä½›å†ˆåŽ¿",
	    "441823": "é˜³å±±åŽ¿",
	    "441825": "è¿žå±±å£®æ—ç‘¶æ—è‡ªæ²»åŽ¿",
	    "441826": "è¿žå—ç‘¶æ—è‡ªæ²»åŽ¿",
	    "441827": "æ¸…æ–°åŒº",
	    "441881": "è‹±å¾·å¸‚",
	    "441882": "è¿žå·žå¸‚",
	    "441883": "å…¶å®ƒåŒº",
	    "441900": "ä¸œèŽžå¸‚",
	    "442000": "ä¸­å±±å¸‚",
	    "442101": "ä¸œæ²™ç¾¤å²›",
	    "445100": "æ½®å·žå¸‚",
	    "445102": "æ¹˜æ¡¥åŒº",
	    "445121": "æ½®å®‰åŒº",
	    "445122": "é¥¶å¹³åŽ¿",
	    "445186": "å…¶å®ƒåŒº",
	    "445200": "æ­é˜³å¸‚",
	    "445202": "æ¦•åŸŽåŒº",
	    "445221": "æ­ä¸œåŒº",
	    "445222": "æ­è¥¿åŽ¿",
	    "445224": "æƒ æ¥åŽ¿",
	    "445281": "æ™®å®å¸‚",
	    "445285": "å…¶å®ƒåŒº",
	    "445300": "äº‘æµ®å¸‚",
	    "445302": "äº‘åŸŽåŒº",
	    "445321": "æ–°å…´åŽ¿",
	    "445322": "éƒå—åŽ¿",
	    "445323": "äº‘å®‰åŽ¿",
	    "445381": "ç½—å®šå¸‚",
	    "445382": "å…¶å®ƒåŒº",
	    "450000": "å¹¿è¥¿å£®æ—è‡ªæ²»åŒº",
	    "450100": "å—å®å¸‚",
	    "450102": "å…´å®åŒº",
	    "450103": "é’ç§€åŒº",
	    "450105": "æ±Ÿå—åŒº",
	    "450107": "è¥¿ä¹¡å¡˜åŒº",
	    "450108": "è‰¯åº†åŒº",
	    "450109": "é‚•å®åŒº",
	    "450122": "æ­¦é¸£åŽ¿",
	    "450123": "éš†å®‰åŽ¿",
	    "450124": "é©¬å±±åŽ¿",
	    "450125": "ä¸Šæž—åŽ¿",
	    "450126": "å®¾é˜³åŽ¿",
	    "450127": "æ¨ªåŽ¿",
	    "450128": "å…¶å®ƒåŒº",
	    "450200": "æŸ³å·žå¸‚",
	    "450202": "åŸŽä¸­åŒº",
	    "450203": "é±¼å³°åŒº",
	    "450204": "æŸ³å—åŒº",
	    "450205": "æŸ³åŒ—åŒº",
	    "450221": "æŸ³æ±ŸåŽ¿",
	    "450222": "æŸ³åŸŽåŽ¿",
	    "450223": "é¹¿å¯¨åŽ¿",
	    "450224": "èžå®‰åŽ¿",
	    "450225": "èžæ°´è‹—æ—è‡ªæ²»åŽ¿",
	    "450226": "ä¸‰æ±Ÿä¾—æ—è‡ªæ²»åŽ¿",
	    "450227": "å…¶å®ƒåŒº",
	    "450300": "æ¡‚æž—å¸‚",
	    "450302": "ç§€å³°åŒº",
	    "450303": "å å½©åŒº",
	    "450304": "è±¡å±±åŒº",
	    "450305": "ä¸ƒæ˜ŸåŒº",
	    "450311": "é›å±±åŒº",
	    "450321": "é˜³æœ”åŽ¿",
	    "450322": "ä¸´æ¡‚åŒº",
	    "450323": "çµå·åŽ¿",
	    "450324": "å…¨å·žåŽ¿",
	    "450325": "å…´å®‰åŽ¿",
	    "450326": "æ°¸ç¦åŽ¿",
	    "450327": "çŒé˜³åŽ¿",
	    "450328": "é¾™èƒœå„æ—è‡ªæ²»åŽ¿",
	    "450329": "èµ„æºåŽ¿",
	    "450330": "å¹³ä¹åŽ¿",
	    "450331": "è”æµ¦åŽ¿",
	    "450332": "æ­åŸŽç‘¶æ—è‡ªæ²»åŽ¿",
	    "450333": "å…¶å®ƒåŒº",
	    "450400": "æ¢§å·žå¸‚",
	    "450403": "ä¸‡ç§€åŒº",
	    "450405": "é•¿æ´²åŒº",
	    "450406": "é¾™åœ©åŒº",
	    "450421": "è‹æ¢§åŽ¿",
	    "450422": "è—¤åŽ¿",
	    "450423": "è’™å±±åŽ¿",
	    "450481": "å²‘æºªå¸‚",
	    "450482": "å…¶å®ƒåŒº",
	    "450500": "åŒ—æµ·å¸‚",
	    "450502": "æµ·åŸŽåŒº",
	    "450503": "é“¶æµ·åŒº",
	    "450512": "é“å±±æ¸¯åŒº",
	    "450521": "åˆæµ¦åŽ¿",
	    "450522": "å…¶å®ƒåŒº",
	    "450600": "é˜²åŸŽæ¸¯å¸‚",
	    "450602": "æ¸¯å£åŒº",
	    "450603": "é˜²åŸŽåŒº",
	    "450621": "ä¸Šæ€åŽ¿",
	    "450681": "ä¸œå…´å¸‚",
	    "450682": "å…¶å®ƒåŒº",
	    "450700": "é’¦å·žå¸‚",
	    "450702": "é’¦å—åŒº",
	    "450703": "é’¦åŒ—åŒº",
	    "450721": "çµå±±åŽ¿",
	    "450722": "æµ¦åŒ—åŽ¿",
	    "450723": "å…¶å®ƒåŒº",
	    "450800": "è´µæ¸¯å¸‚",
	    "450802": "æ¸¯åŒ—åŒº",
	    "450803": "æ¸¯å—åŒº",
	    "450804": "è¦ƒå¡˜åŒº",
	    "450821": "å¹³å—åŽ¿",
	    "450881": "æ¡‚å¹³å¸‚",
	    "450882": "å…¶å®ƒåŒº",
	    "450900": "çŽ‰æž—å¸‚",
	    "450902": "çŽ‰å·žåŒº",
	    "450903": "ç¦ç»µåŒº",
	    "450921": "å®¹åŽ¿",
	    "450922": "é™†å·åŽ¿",
	    "450923": "åšç™½åŽ¿",
	    "450924": "å…´ä¸šåŽ¿",
	    "450981": "åŒ—æµå¸‚",
	    "450982": "å…¶å®ƒåŒº",
	    "451000": "ç™¾è‰²å¸‚",
	    "451002": "å³æ±ŸåŒº",
	    "451021": "ç”°é˜³åŽ¿",
	    "451022": "ç”°ä¸œåŽ¿",
	    "451023": "å¹³æžœåŽ¿",
	    "451024": "å¾·ä¿åŽ¿",
	    "451025": "é–è¥¿åŽ¿",
	    "451026": "é‚£å¡åŽ¿",
	    "451027": "å‡Œäº‘åŽ¿",
	    "451028": "ä¹ä¸šåŽ¿",
	    "451029": "ç”°æž—åŽ¿",
	    "451030": "è¥¿æž—åŽ¿",
	    "451031": "éš†æž—å„æ—è‡ªæ²»åŽ¿",
	    "451032": "å…¶å®ƒåŒº",
	    "451100": "è´ºå·žå¸‚",
	    "451102": "å…«æ­¥åŒº",
	    "451119": "å¹³æ¡‚ç®¡ç†åŒº",
	    "451121": "æ˜­å¹³åŽ¿",
	    "451122": "é’Ÿå±±åŽ¿",
	    "451123": "å¯Œå·ç‘¶æ—è‡ªæ²»åŽ¿",
	    "451124": "å…¶å®ƒåŒº",
	    "451200": "æ²³æ± å¸‚",
	    "451202": "é‡‘åŸŽæ±ŸåŒº",
	    "451221": "å—ä¸¹åŽ¿",
	    "451222": "å¤©å³¨åŽ¿",
	    "451223": "å‡¤å±±åŽ¿",
	    "451224": "ä¸œå…°åŽ¿",
	    "451225": "ç½—åŸŽä»«ä½¬æ—è‡ªæ²»åŽ¿",
	    "451226": "çŽ¯æ±Ÿæ¯›å—æ—è‡ªæ²»åŽ¿",
	    "451227": "å·´é©¬ç‘¶æ—è‡ªæ²»åŽ¿",
	    "451228": "éƒ½å®‰ç‘¶æ—è‡ªæ²»åŽ¿",
	    "451229": "å¤§åŒ–ç‘¶æ—è‡ªæ²»åŽ¿",
	    "451281": "å®œå·žå¸‚",
	    "451282": "å…¶å®ƒåŒº",
	    "451300": "æ¥å®¾å¸‚",
	    "451302": "å…´å®¾åŒº",
	    "451321": "å¿»åŸŽåŽ¿",
	    "451322": "è±¡å·žåŽ¿",
	    "451323": "æ­¦å®£åŽ¿",
	    "451324": "é‡‘ç§€ç‘¶æ—è‡ªæ²»åŽ¿",
	    "451381": "åˆå±±å¸‚",
	    "451382": "å…¶å®ƒåŒº",
	    "451400": "å´‡å·¦å¸‚",
	    "451402": "æ±Ÿå·žåŒº",
	    "451421": "æ‰¶ç»¥åŽ¿",
	    "451422": "å®æ˜ŽåŽ¿",
	    "451423": "é¾™å·žåŽ¿",
	    "451424": "å¤§æ–°åŽ¿",
	    "451425": "å¤©ç­‰åŽ¿",
	    "451481": "å‡­ç¥¥å¸‚",
	    "451482": "å…¶å®ƒåŒº",
	    "460000": "æµ·å—çœ",
	    "460100": "æµ·å£å¸‚",
	    "460105": "ç§€è‹±åŒº",
	    "460106": "é¾™åŽåŒº",
	    "460107": "ç¼å±±åŒº",
	    "460108": "ç¾Žå…°åŒº",
	    "460109": "å…¶å®ƒåŒº",
	    "460200": "ä¸‰äºšå¸‚",
	    "460300": "ä¸‰æ²™å¸‚",
	    "460321": "è¥¿æ²™ç¾¤å²›",
	    "460322": "å—æ²™ç¾¤å²›",
	    "460323": "ä¸­æ²™ç¾¤å²›çš„å²›ç¤åŠå…¶æµ·åŸŸ",
	    "469001": "äº”æŒ‡å±±å¸‚",
	    "469002": "ç¼æµ·å¸‚",
	    "469003": "å„‹å·žå¸‚",
	    "469005": "æ–‡æ˜Œå¸‚",
	    "469006": "ä¸‡å®å¸‚",
	    "469007": "ä¸œæ–¹å¸‚",
	    "469025": "å®šå®‰åŽ¿",
	    "469026": "å±¯æ˜ŒåŽ¿",
	    "469027": "æ¾„è¿ˆåŽ¿",
	    "469028": "ä¸´é«˜åŽ¿",
	    "469030": "ç™½æ²™é»Žæ—è‡ªæ²»åŽ¿",
	    "469031": "æ˜Œæ±Ÿé»Žæ—è‡ªæ²»åŽ¿",
	    "469033": "ä¹ä¸œé»Žæ—è‡ªæ²»åŽ¿",
	    "469034": "é™µæ°´é»Žæ—è‡ªæ²»åŽ¿",
	    "469035": "ä¿äº­é»Žæ—è‹—æ—è‡ªæ²»åŽ¿",
	    "469036": "ç¼ä¸­é»Žæ—è‹—æ—è‡ªæ²»åŽ¿",
	    "471005": "å…¶å®ƒåŒº",
	    "500000": "é‡åº†",
	    "500100": "é‡åº†å¸‚",
	    "500101": "ä¸‡å·žåŒº",
	    "500102": "æ¶ªé™µåŒº",
	    "500103": "æ¸ä¸­åŒº",
	    "500104": "å¤§æ¸¡å£åŒº",
	    "500105": "æ±ŸåŒ—åŒº",
	    "500106": "æ²™åªååŒº",
	    "500107": "ä¹é¾™å¡åŒº",
	    "500108": "å—å²¸åŒº",
	    "500109": "åŒ—ç¢šåŒº",
	    "500110": "ä¸‡ç››åŒº",
	    "500111": "åŒæ¡¥åŒº",
	    "500112": "æ¸åŒ—åŒº",
	    "500113": "å·´å—åŒº",
	    "500114": "é»”æ±ŸåŒº",
	    "500115": "é•¿å¯¿åŒº",
	    "500222": "ç¶¦æ±ŸåŒº",
	    "500223": "æ½¼å—åŽ¿",
	    "500224": "é“œæ¢åŽ¿",
	    "500225": "å¤§è¶³åŒº",
	    "500226": "è£æ˜ŒåŽ¿",
	    "500227": "ç’§å±±åŽ¿",
	    "500228": "æ¢å¹³åŽ¿",
	    "500229": "åŸŽå£åŽ¿",
	    "500230": "ä¸°éƒ½åŽ¿",
	    "500231": "åž«æ±ŸåŽ¿",
	    "500232": "æ­¦éš†åŽ¿",
	    "500233": "å¿ åŽ¿",
	    "500234": "å¼€åŽ¿",
	    "500235": "äº‘é˜³åŽ¿",
	    "500236": "å¥‰èŠ‚åŽ¿",
	    "500237": "å·«å±±åŽ¿",
	    "500238": "å·«æºªåŽ¿",
	    "500240": "çŸ³æŸ±åœŸå®¶æ—è‡ªæ²»åŽ¿",
	    "500241": "ç§€å±±åœŸå®¶æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "500242": "é…‰é˜³åœŸå®¶æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "500243": "å½­æ°´è‹—æ—åœŸå®¶æ—è‡ªæ²»åŽ¿",
	    "500381": "æ±Ÿæ´¥åŒº",
	    "500382": "åˆå·åŒº",
	    "500383": "æ°¸å·åŒº",
	    "500384": "å—å·åŒº",
	    "500385": "å…¶å®ƒåŒº",
	    "510000": "å››å·çœ",
	    "510100": "æˆéƒ½å¸‚",
	    "510104": "é”¦æ±ŸåŒº",
	    "510105": "é’ç¾ŠåŒº",
	    "510106": "é‡‘ç‰›åŒº",
	    "510107": "æ­¦ä¾¯åŒº",
	    "510108": "æˆåŽåŒº",
	    "510112": "é¾™æ³‰é©¿åŒº",
	    "510113": "é’ç™½æ±ŸåŒº",
	    "510114": "æ–°éƒ½åŒº",
	    "510115": "æ¸©æ±ŸåŒº",
	    "510121": "é‡‘å ‚åŽ¿",
	    "510122": "åŒæµåŽ¿",
	    "510124": "éƒ«åŽ¿",
	    "510129": "å¤§é‚‘åŽ¿",
	    "510131": "è’²æ±ŸåŽ¿",
	    "510132": "æ–°æ´¥åŽ¿",
	    "510181": "éƒ½æ±Ÿå °å¸‚",
	    "510182": "å½­å·žå¸‚",
	    "510183": "é‚›å´ƒå¸‚",
	    "510184": "å´‡å·žå¸‚",
	    "510185": "å…¶å®ƒåŒº",
	    "510300": "è‡ªè´¡å¸‚",
	    "510302": "è‡ªæµäº•åŒº",
	    "510303": "è´¡äº•åŒº",
	    "510304": "å¤§å®‰åŒº",
	    "510311": "æ²¿æ»©åŒº",
	    "510321": "è£åŽ¿",
	    "510322": "å¯Œé¡ºåŽ¿",
	    "510323": "å…¶å®ƒåŒº",
	    "510400": "æ”€æžèŠ±å¸‚",
	    "510402": "ä¸œåŒº",
	    "510403": "è¥¿åŒº",
	    "510411": "ä»å’ŒåŒº",
	    "510421": "ç±³æ˜“åŽ¿",
	    "510422": "ç›è¾¹åŽ¿",
	    "510423": "å…¶å®ƒåŒº",
	    "510500": "æ³¸å·žå¸‚",
	    "510502": "æ±Ÿé˜³åŒº",
	    "510503": "çº³æºªåŒº",
	    "510504": "é¾™é©¬æ½­åŒº",
	    "510521": "æ³¸åŽ¿",
	    "510522": "åˆæ±ŸåŽ¿",
	    "510524": "å™æ°¸åŽ¿",
	    "510525": "å¤è”ºåŽ¿",
	    "510526": "å…¶å®ƒåŒº",
	    "510600": "å¾·é˜³å¸‚",
	    "510603": "æ—Œé˜³åŒº",
	    "510623": "ä¸­æ±ŸåŽ¿",
	    "510626": "ç½—æ±ŸåŽ¿",
	    "510681": "å¹¿æ±‰å¸‚",
	    "510682": "ä»€é‚¡å¸‚",
	    "510683": "ç»µç«¹å¸‚",
	    "510684": "å…¶å®ƒåŒº",
	    "510700": "ç»µé˜³å¸‚",
	    "510703": "æ¶ªåŸŽåŒº",
	    "510704": "æ¸¸ä»™åŒº",
	    "510722": "ä¸‰å°åŽ¿",
	    "510723": "ç›äº­åŽ¿",
	    "510724": "å®‰åŽ¿",
	    "510725": "æ¢“æ½¼åŽ¿",
	    "510726": "åŒ—å·ç¾Œæ—è‡ªæ²»åŽ¿",
	    "510727": "å¹³æ­¦åŽ¿",
	    "510781": "æ±Ÿæ²¹å¸‚",
	    "510782": "å…¶å®ƒåŒº",
	    "510800": "å¹¿å…ƒå¸‚",
	    "510802": "åˆ©å·žåŒº",
	    "510811": "æ˜­åŒ–åŒº",
	    "510812": "æœå¤©åŒº",
	    "510821": "æ—ºè‹åŽ¿",
	    "510822": "é’å·åŽ¿",
	    "510823": "å‰‘é˜åŽ¿",
	    "510824": "è‹æºªåŽ¿",
	    "510825": "å…¶å®ƒåŒº",
	    "510900": "é‚å®å¸‚",
	    "510903": "èˆ¹å±±åŒº",
	    "510904": "å®‰å±…åŒº",
	    "510921": "è“¬æºªåŽ¿",
	    "510922": "å°„æ´ªåŽ¿",
	    "510923": "å¤§è‹±åŽ¿",
	    "510924": "å…¶å®ƒåŒº",
	    "511000": "å†…æ±Ÿå¸‚",
	    "511002": "å¸‚ä¸­åŒº",
	    "511011": "ä¸œå…´åŒº",
	    "511024": "å¨è¿œåŽ¿",
	    "511025": "èµ„ä¸­åŽ¿",
	    "511028": "éš†æ˜ŒåŽ¿",
	    "511029": "å…¶å®ƒåŒº",
	    "511100": "ä¹å±±å¸‚",
	    "511102": "å¸‚ä¸­åŒº",
	    "511111": "æ²™æ¹¾åŒº",
	    "511112": "äº”é€šæ¡¥åŒº",
	    "511113": "é‡‘å£æ²³åŒº",
	    "511123": "çŠä¸ºåŽ¿",
	    "511124": "äº•ç ”åŽ¿",
	    "511126": "å¤¹æ±ŸåŽ¿",
	    "511129": "æ²å·åŽ¿",
	    "511132": "å³¨è¾¹å½æ—è‡ªæ²»åŽ¿",
	    "511133": "é©¬è¾¹å½æ—è‡ªæ²»åŽ¿",
	    "511181": "å³¨çœ‰å±±å¸‚",
	    "511182": "å…¶å®ƒåŒº",
	    "511300": "å—å……å¸‚",
	    "511302": "é¡ºåº†åŒº",
	    "511303": "é«˜åªåŒº",
	    "511304": "å˜‰é™µåŒº",
	    "511321": "å—éƒ¨åŽ¿",
	    "511322": "è¥å±±åŽ¿",
	    "511323": "è“¬å®‰åŽ¿",
	    "511324": "ä»ªé™‡åŽ¿",
	    "511325": "è¥¿å……åŽ¿",
	    "511381": "é˜†ä¸­å¸‚",
	    "511382": "å…¶å®ƒåŒº",
	    "511400": "çœ‰å±±å¸‚",
	    "511402": "ä¸œå¡åŒº",
	    "511421": "ä»å¯¿åŽ¿",
	    "511422": "å½­å±±åŽ¿",
	    "511423": "æ´ªé›…åŽ¿",
	    "511424": "ä¸¹æ£±åŽ¿",
	    "511425": "é’ç¥žåŽ¿",
	    "511426": "å…¶å®ƒåŒº",
	    "511500": "å®œå®¾å¸‚",
	    "511502": "ç¿ å±åŒº",
	    "511521": "å®œå®¾åŽ¿",
	    "511522": "å—æºªåŒº",
	    "511523": "æ±Ÿå®‰åŽ¿",
	    "511524": "é•¿å®åŽ¿",
	    "511525": "é«˜åŽ¿",
	    "511526": "ç™åŽ¿",
	    "511527": "ç­ è¿žåŽ¿",
	    "511528": "å…´æ–‡åŽ¿",
	    "511529": "å±å±±åŽ¿",
	    "511530": "å…¶å®ƒåŒº",
	    "511600": "å¹¿å®‰å¸‚",
	    "511602": "å¹¿å®‰åŒº",
	    "511603": "å‰é”‹åŒº",
	    "511621": "å²³æ± åŽ¿",
	    "511622": "æ­¦èƒœåŽ¿",
	    "511623": "é‚»æ°´åŽ¿",
	    "511681": "åŽè“¥å¸‚",
	    "511683": "å…¶å®ƒåŒº",
	    "511700": "è¾¾å·žå¸‚",
	    "511702": "é€šå·åŒº",
	    "511721": "è¾¾å·åŒº",
	    "511722": "å®£æ±‰åŽ¿",
	    "511723": "å¼€æ±ŸåŽ¿",
	    "511724": "å¤§ç«¹åŽ¿",
	    "511725": "æ¸ åŽ¿",
	    "511781": "ä¸‡æºå¸‚",
	    "511782": "å…¶å®ƒåŒº",
	    "511800": "é›…å®‰å¸‚",
	    "511802": "é›¨åŸŽåŒº",
	    "511821": "åå±±åŒº",
	    "511822": "è¥ç»åŽ¿",
	    "511823": "æ±‰æºåŽ¿",
	    "511824": "çŸ³æ£‰åŽ¿",
	    "511825": "å¤©å…¨åŽ¿",
	    "511826": "èŠ¦å±±åŽ¿",
	    "511827": "å®å…´åŽ¿",
	    "511828": "å…¶å®ƒåŒº",
	    "511900": "å·´ä¸­å¸‚",
	    "511902": "å·´å·žåŒº",
	    "511903": "æ©é˜³åŒº",
	    "511921": "é€šæ±ŸåŽ¿",
	    "511922": "å—æ±ŸåŽ¿",
	    "511923": "å¹³æ˜ŒåŽ¿",
	    "511924": "å…¶å®ƒåŒº",
	    "512000": "èµ„é˜³å¸‚",
	    "512002": "é›æ±ŸåŒº",
	    "512021": "å®‰å²³åŽ¿",
	    "512022": "ä¹è‡³åŽ¿",
	    "512081": "ç®€é˜³å¸‚",
	    "512082": "å…¶å®ƒåŒº",
	    "513200": "é˜¿åè—æ—ç¾Œæ—è‡ªæ²»å·ž",
	    "513221": "æ±¶å·åŽ¿",
	    "513222": "ç†åŽ¿",
	    "513223": "èŒ‚åŽ¿",
	    "513224": "æ¾æ½˜åŽ¿",
	    "513225": "ä¹å¯¨æ²ŸåŽ¿",
	    "513226": "é‡‘å·åŽ¿",
	    "513227": "å°é‡‘åŽ¿",
	    "513228": "é»‘æ°´åŽ¿",
	    "513229": "é©¬å°”åº·åŽ¿",
	    "513230": "å£¤å¡˜åŽ¿",
	    "513231": "é˜¿ååŽ¿",
	    "513232": "è‹¥å°”ç›–åŽ¿",
	    "513233": "çº¢åŽŸåŽ¿",
	    "513234": "å…¶å®ƒåŒº",
	    "513300": "ç”˜å­œè—æ—è‡ªæ²»å·ž",
	    "513321": "åº·å®šåŽ¿",
	    "513322": "æ³¸å®šåŽ¿",
	    "513323": "ä¸¹å·´åŽ¿",
	    "513324": "ä¹é¾™åŽ¿",
	    "513325": "é›…æ±ŸåŽ¿",
	    "513326": "é“å­šåŽ¿",
	    "513327": "ç‚‰éœåŽ¿",
	    "513328": "ç”˜å­œåŽ¿",
	    "513329": "æ–°é¾™åŽ¿",
	    "513330": "å¾·æ ¼åŽ¿",
	    "513331": "ç™½çŽ‰åŽ¿",
	    "513332": "çŸ³æ¸ åŽ¿",
	    "513333": "è‰²è¾¾åŽ¿",
	    "513334": "ç†å¡˜åŽ¿",
	    "513335": "å·´å¡˜åŽ¿",
	    "513336": "ä¹¡åŸŽåŽ¿",
	    "513337": "ç¨»åŸŽåŽ¿",
	    "513338": "å¾—è£åŽ¿",
	    "513339": "å…¶å®ƒåŒº",
	    "513400": "å‡‰å±±å½æ—è‡ªæ²»å·ž",
	    "513401": "è¥¿æ˜Œå¸‚",
	    "513422": "æœ¨é‡Œè—æ—è‡ªæ²»åŽ¿",
	    "513423": "ç›æºåŽ¿",
	    "513424": "å¾·æ˜ŒåŽ¿",
	    "513425": "ä¼šç†åŽ¿",
	    "513426": "ä¼šä¸œåŽ¿",
	    "513427": "å®å—åŽ¿",
	    "513428": "æ™®æ ¼åŽ¿",
	    "513429": "å¸ƒæ‹–åŽ¿",
	    "513430": "é‡‘é˜³åŽ¿",
	    "513431": "æ˜­è§‰åŽ¿",
	    "513432": "å–œå¾·åŽ¿",
	    "513433": "å†•å®åŽ¿",
	    "513434": "è¶Šè¥¿åŽ¿",
	    "513435": "ç”˜æ´›åŽ¿",
	    "513436": "ç¾Žå§‘åŽ¿",
	    "513437": "é›·æ³¢åŽ¿",
	    "513438": "å…¶å®ƒåŒº",
	    "520000": "è´µå·žçœ",
	    "520100": "è´µé˜³å¸‚",
	    "520102": "å—æ˜ŽåŒº",
	    "520103": "äº‘å²©åŒº",
	    "520111": "èŠ±æºªåŒº",
	    "520112": "ä¹Œå½“åŒº",
	    "520113": "ç™½äº‘åŒº",
	    "520121": "å¼€é˜³åŽ¿",
	    "520122": "æ¯çƒ½åŽ¿",
	    "520123": "ä¿®æ–‡åŽ¿",
	    "520151": "è§‚å±±æ¹–åŒº",
	    "520181": "æ¸…é•‡å¸‚",
	    "520182": "å…¶å®ƒåŒº",
	    "520200": "å…­ç›˜æ°´å¸‚",
	    "520201": "é’Ÿå±±åŒº",
	    "520203": "å…­æžç‰¹åŒº",
	    "520221": "æ°´åŸŽåŽ¿",
	    "520222": "ç›˜åŽ¿",
	    "520223": "å…¶å®ƒåŒº",
	    "520300": "éµä¹‰å¸‚",
	    "520302": "çº¢èŠ±å²—åŒº",
	    "520303": "æ±‡å·åŒº",
	    "520321": "éµä¹‰åŽ¿",
	    "520322": "æ¡æ¢“åŽ¿",
	    "520323": "ç»¥é˜³åŽ¿",
	    "520324": "æ­£å®‰åŽ¿",
	    "520325": "é“çœŸä»¡ä½¬æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "520326": "åŠ¡å·ä»¡ä½¬æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "520327": "å‡¤å†ˆåŽ¿",
	    "520328": "æ¹„æ½­åŽ¿",
	    "520329": "ä½™åº†åŽ¿",
	    "520330": "ä¹ æ°´åŽ¿",
	    "520381": "èµ¤æ°´å¸‚",
	    "520382": "ä»æ€€å¸‚",
	    "520383": "å…¶å®ƒåŒº",
	    "520400": "å®‰é¡ºå¸‚",
	    "520402": "è¥¿ç§€åŒº",
	    "520421": "å¹³ååŽ¿",
	    "520422": "æ™®å®šåŽ¿",
	    "520423": "é•‡å®å¸ƒä¾æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "520424": "å…³å²­å¸ƒä¾æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "520425": "ç´«äº‘è‹—æ—å¸ƒä¾æ—è‡ªæ²»åŽ¿",
	    "520426": "å…¶å®ƒåŒº",
	    "522200": "é“œä»å¸‚",
	    "522201": "ç¢§æ±ŸåŒº",
	    "522222": "æ±Ÿå£åŽ¿",
	    "522223": "çŽ‰å±ä¾—æ—è‡ªæ²»åŽ¿",
	    "522224": "çŸ³é˜¡åŽ¿",
	    "522225": "æ€å—åŽ¿",
	    "522226": "å°æ±ŸåœŸå®¶æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "522227": "å¾·æ±ŸåŽ¿",
	    "522228": "æ²¿æ²³åœŸå®¶æ—è‡ªæ²»åŽ¿",
	    "522229": "æ¾æ¡ƒè‹—æ—è‡ªæ²»åŽ¿",
	    "522230": "ä¸‡å±±åŒº",
	    "522231": "å…¶å®ƒåŒº",
	    "522300": "é»”è¥¿å—å¸ƒä¾æ—è‹—æ—è‡ªæ²»å·ž",
	    "522301": "å…´ä¹‰å¸‚",
	    "522322": "å…´ä»åŽ¿",
	    "522323": "æ™®å®‰åŽ¿",
	    "522324": "æ™´éš†åŽ¿",
	    "522325": "è´žä¸°åŽ¿",
	    "522326": "æœ›è°ŸåŽ¿",
	    "522327": "å†Œäº¨åŽ¿",
	    "522328": "å®‰é¾™åŽ¿",
	    "522329": "å…¶å®ƒåŒº",
	    "522400": "æ¯•èŠ‚å¸‚",
	    "522401": "ä¸ƒæ˜Ÿå…³åŒº",
	    "522422": "å¤§æ–¹åŽ¿",
	    "522423": "é»”è¥¿åŽ¿",
	    "522424": "é‡‘æ²™åŽ¿",
	    "522425": "ç»‡é‡‘åŽ¿",
	    "522426": "çº³é›åŽ¿",
	    "522427": "å¨å®å½æ—å›žæ—è‹—æ—è‡ªæ²»åŽ¿",
	    "522428": "èµ«ç« åŽ¿",
	    "522429": "å…¶å®ƒåŒº",
	    "522600": "é»”ä¸œå—è‹—æ—ä¾—æ—è‡ªæ²»å·ž",
	    "522601": "å‡¯é‡Œå¸‚",
	    "522622": "é»„å¹³åŽ¿",
	    "522623": "æ–½ç§‰åŽ¿",
	    "522624": "ä¸‰ç©—åŽ¿",
	    "522625": "é•‡è¿œåŽ¿",
	    "522626": "å²‘å·©åŽ¿",
	    "522627": "å¤©æŸ±åŽ¿",
	    "522628": "é”¦å±åŽ¿",
	    "522629": "å‰‘æ²³åŽ¿",
	    "522630": "å°æ±ŸåŽ¿",
	    "522631": "é»Žå¹³åŽ¿",
	    "522632": "æ¦•æ±ŸåŽ¿",
	    "522633": "ä»Žæ±ŸåŽ¿",
	    "522634": "é›·å±±åŽ¿",
	    "522635": "éº»æ±ŸåŽ¿",
	    "522636": "ä¸¹å¯¨åŽ¿",
	    "522637": "å…¶å®ƒåŒº",
	    "522700": "é»”å—å¸ƒä¾æ—è‹—æ—è‡ªæ²»å·ž",
	    "522701": "éƒ½åŒ€å¸‚",
	    "522702": "ç¦æ³‰å¸‚",
	    "522722": "è”æ³¢åŽ¿",
	    "522723": "è´µå®šåŽ¿",
	    "522725": "ç“®å®‰åŽ¿",
	    "522726": "ç‹¬å±±åŽ¿",
	    "522727": "å¹³å¡˜åŽ¿",
	    "522728": "ç½—ç”¸åŽ¿",
	    "522729": "é•¿é¡ºåŽ¿",
	    "522730": "é¾™é‡ŒåŽ¿",
	    "522731": "æƒ æ°´åŽ¿",
	    "522732": "ä¸‰éƒ½æ°´æ—è‡ªæ²»åŽ¿",
	    "522733": "å…¶å®ƒåŒº",
	    "530000": "äº‘å—çœ",
	    "530100": "æ˜†æ˜Žå¸‚",
	    "530102": "äº”åŽåŒº",
	    "530103": "ç›˜é¾™åŒº",
	    "530111": "å®˜æ¸¡åŒº",
	    "530112": "è¥¿å±±åŒº",
	    "530113": "ä¸œå·åŒº",
	    "530121": "å‘ˆè´¡åŒº",
	    "530122": "æ™‹å®åŽ¿",
	    "530124": "å¯Œæ°‘åŽ¿",
	    "530125": "å®œè‰¯åŽ¿",
	    "530126": "çŸ³æž—å½æ—è‡ªæ²»åŽ¿",
	    "530127": "åµ©æ˜ŽåŽ¿",
	    "530128": "ç¦„åŠå½æ—è‹—æ—è‡ªæ²»åŽ¿",
	    "530129": "å¯»ç”¸å›žæ—å½æ—è‡ªæ²»åŽ¿",
	    "530181": "å®‰å®å¸‚",
	    "530182": "å…¶å®ƒåŒº",
	    "530300": "æ›²é–å¸‚",
	    "530302": "éº’éºŸåŒº",
	    "530321": "é©¬é¾™åŽ¿",
	    "530322": "é™†è‰¯åŽ¿",
	    "530323": "å¸ˆå®—åŽ¿",
	    "530324": "ç½—å¹³åŽ¿",
	    "530325": "å¯ŒæºåŽ¿",
	    "530326": "ä¼šæ³½åŽ¿",
	    "530328": "æ²¾ç›ŠåŽ¿",
	    "530381": "å®£å¨å¸‚",
	    "530382": "å…¶å®ƒåŒº",
	    "530400": "çŽ‰æºªå¸‚",
	    "530402": "çº¢å¡”åŒº",
	    "530421": "æ±Ÿå·åŽ¿",
	    "530422": "æ¾„æ±ŸåŽ¿",
	    "530423": "é€šæµ·åŽ¿",
	    "530424": "åŽå®åŽ¿",
	    "530425": "æ˜“é—¨åŽ¿",
	    "530426": "å³¨å±±å½æ—è‡ªæ²»åŽ¿",
	    "530427": "æ–°å¹³å½æ—å‚£æ—è‡ªæ²»åŽ¿",
	    "530428": "å…ƒæ±Ÿå“ˆå°¼æ—å½æ—å‚£æ—è‡ªæ²»åŽ¿",
	    "530429": "å…¶å®ƒåŒº",
	    "530500": "ä¿å±±å¸‚",
	    "530502": "éš†é˜³åŒº",
	    "530521": "æ–½ç”¸åŽ¿",
	    "530522": "è…¾å†²åŽ¿",
	    "530523": "é¾™é™µåŽ¿",
	    "530524": "æ˜Œå®åŽ¿",
	    "530525": "å…¶å®ƒåŒº",
	    "530600": "æ˜­é€šå¸‚",
	    "530602": "æ˜­é˜³åŒº",
	    "530621": "é²ç”¸åŽ¿",
	    "530622": "å·§å®¶åŽ¿",
	    "530623": "ç›æ´¥åŽ¿",
	    "530624": "å¤§å…³åŽ¿",
	    "530625": "æ°¸å–„åŽ¿",
	    "530626": "ç»¥æ±ŸåŽ¿",
	    "530627": "é•‡é›„åŽ¿",
	    "530628": "å½è‰¯åŽ¿",
	    "530629": "å¨ä¿¡åŽ¿",
	    "530630": "æ°´å¯ŒåŽ¿",
	    "530631": "å…¶å®ƒåŒº",
	    "530700": "ä¸½æ±Ÿå¸‚",
	    "530702": "å¤åŸŽåŒº",
	    "530721": "çŽ‰é¾™çº³è¥¿æ—è‡ªæ²»åŽ¿",
	    "530722": "æ°¸èƒœåŽ¿",
	    "530723": "åŽåªåŽ¿",
	    "530724": "å®è’—å½æ—è‡ªæ²»åŽ¿",
	    "530725": "å…¶å®ƒåŒº",
	    "530800": "æ™®æ´±å¸‚",
	    "530802": "æ€èŒ…åŒº",
	    "530821": "å®æ´±å“ˆå°¼æ—å½æ—è‡ªæ²»åŽ¿",
	    "530822": "å¢¨æ±Ÿå“ˆå°¼æ—è‡ªæ²»åŽ¿",
	    "530823": "æ™¯ä¸œå½æ—è‡ªæ²»åŽ¿",
	    "530824": "æ™¯è°·å‚£æ—å½æ—è‡ªæ²»åŽ¿",
	    "530825": "é•‡æ²…å½æ—å“ˆå°¼æ—æ‹‰ç¥œæ—è‡ªæ²»åŽ¿",
	    "530826": "æ±ŸåŸŽå“ˆå°¼æ—å½æ—è‡ªæ²»åŽ¿",
	    "530827": "å­Ÿè¿žå‚£æ—æ‹‰ç¥œæ—ä½¤æ—è‡ªæ²»åŽ¿",
	    "530828": "æ¾œæ²§æ‹‰ç¥œæ—è‡ªæ²»åŽ¿",
	    "530829": "è¥¿ç›Ÿä½¤æ—è‡ªæ²»åŽ¿",
	    "530830": "å…¶å®ƒåŒº",
	    "530900": "ä¸´æ²§å¸‚",
	    "530902": "ä¸´ç¿”åŒº",
	    "530921": "å‡¤åº†åŽ¿",
	    "530922": "äº‘åŽ¿",
	    "530923": "æ°¸å¾·åŽ¿",
	    "530924": "é•‡åº·åŽ¿",
	    "530925": "åŒæ±Ÿæ‹‰ç¥œæ—ä½¤æ—å¸ƒæœ—æ—å‚£æ—è‡ªæ²»åŽ¿",
	    "530926": "è€¿é©¬å‚£æ—ä½¤æ—è‡ªæ²»åŽ¿",
	    "530927": "æ²§æºä½¤æ—è‡ªæ²»åŽ¿",
	    "530928": "å…¶å®ƒåŒº",
	    "532300": "æ¥šé›„å½æ—è‡ªæ²»å·ž",
	    "532301": "æ¥šé›„å¸‚",
	    "532322": "åŒæŸåŽ¿",
	    "532323": "ç‰Ÿå®šåŽ¿",
	    "532324": "å—åŽåŽ¿",
	    "532325": "å§šå®‰åŽ¿",
	    "532326": "å¤§å§šåŽ¿",
	    "532327": "æ°¸ä»åŽ¿",
	    "532328": "å…ƒè°‹åŽ¿",
	    "532329": "æ­¦å®šåŽ¿",
	    "532331": "ç¦„ä¸°åŽ¿",
	    "532332": "å…¶å®ƒåŒº",
	    "532500": "çº¢æ²³å“ˆå°¼æ—å½æ—è‡ªæ²»å·ž",
	    "532501": "ä¸ªæ—§å¸‚",
	    "532502": "å¼€è¿œå¸‚",
	    "532522": "è’™è‡ªå¸‚",
	    "532523": "å±è¾¹è‹—æ—è‡ªæ²»åŽ¿",
	    "532524": "å»ºæ°´åŽ¿",
	    "532525": "çŸ³å±åŽ¿",
	    "532526": "å¼¥å‹’å¸‚",
	    "532527": "æ³¸è¥¿åŽ¿",
	    "532528": "å…ƒé˜³åŽ¿",
	    "532529": "çº¢æ²³åŽ¿",
	    "532530": "é‡‘å¹³è‹—æ—ç‘¶æ—å‚£æ—è‡ªæ²»åŽ¿",
	    "532531": "ç»¿æ˜¥åŽ¿",
	    "532532": "æ²³å£ç‘¶æ—è‡ªæ²»åŽ¿",
	    "532533": "å…¶å®ƒåŒº",
	    "532600": "æ–‡å±±å£®æ—è‹—æ—è‡ªæ²»å·ž",
	    "532621": "æ–‡å±±å¸‚",
	    "532622": "ç šå±±åŽ¿",
	    "532623": "è¥¿ç•´åŽ¿",
	    "532624": "éº»æ —å¡åŽ¿",
	    "532625": "é©¬å…³åŽ¿",
	    "532626": "ä¸˜åŒ—åŽ¿",
	    "532627": "å¹¿å—åŽ¿",
	    "532628": "å¯Œå®åŽ¿",
	    "532629": "å…¶å®ƒåŒº",
	    "532800": "è¥¿åŒç‰ˆçº³å‚£æ—è‡ªæ²»å·ž",
	    "532801": "æ™¯æ´ªå¸‚",
	    "532822": "å‹æµ·åŽ¿",
	    "532823": "å‹è…ŠåŽ¿",
	    "532824": "å…¶å®ƒåŒº",
	    "532900": "å¤§ç†ç™½æ—è‡ªæ²»å·ž",
	    "532901": "å¤§ç†å¸‚",
	    "532922": "æ¼¾æ¿žå½æ—è‡ªæ²»åŽ¿",
	    "532923": "ç¥¥äº‘åŽ¿",
	    "532924": "å®¾å·åŽ¿",
	    "532925": "å¼¥æ¸¡åŽ¿",
	    "532926": "å—æ¶§å½æ—è‡ªæ²»åŽ¿",
	    "532927": "å·å±±å½æ—å›žæ—è‡ªæ²»åŽ¿",
	    "532928": "æ°¸å¹³åŽ¿",
	    "532929": "äº‘é¾™åŽ¿",
	    "532930": "æ´±æºåŽ¿",
	    "532931": "å‰‘å·åŽ¿",
	    "532932": "é¹¤åº†åŽ¿",
	    "532933": "å…¶å®ƒåŒº",
	    "533100": "å¾·å®å‚£æ—æ™¯é¢‡æ—è‡ªæ²»å·ž",
	    "533102": "ç‘žä¸½å¸‚",
	    "533103": "èŠ’å¸‚",
	    "533122": "æ¢æ²³åŽ¿",
	    "533123": "ç›ˆæ±ŸåŽ¿",
	    "533124": "é™‡å·åŽ¿",
	    "533125": "å…¶å®ƒåŒº",
	    "533300": "æ€’æ±Ÿå‚ˆåƒ³æ—è‡ªæ²»å·ž",
	    "533321": "æ³¸æ°´åŽ¿",
	    "533323": "ç¦è´¡åŽ¿",
	    "533324": "è´¡å±±ç‹¬é¾™æ—æ€’æ—è‡ªæ²»åŽ¿",
	    "533325": "å…°åªç™½æ—æ™®ç±³æ—è‡ªæ²»åŽ¿",
	    "533326": "å…¶å®ƒåŒº",
	    "533400": "è¿ªåº†è—æ—è‡ªæ²»å·ž",
	    "533421": "é¦™æ ¼é‡Œæ‹‰åŽ¿",
	    "533422": "å¾·é’¦åŽ¿",
	    "533423": "ç»´è¥¿å‚ˆåƒ³æ—è‡ªæ²»åŽ¿",
	    "533424": "å…¶å®ƒåŒº",
	    "540000": "è¥¿è—è‡ªæ²»åŒº",
	    "540100": "æ‹‰è¨å¸‚",
	    "540102": "åŸŽå…³åŒº",
	    "540121": "æž—å‘¨åŽ¿",
	    "540122": "å½“é›„åŽ¿",
	    "540123": "å°¼æœ¨åŽ¿",
	    "540124": "æ›²æ°´åŽ¿",
	    "540125": "å †é¾™å¾·åº†åŽ¿",
	    "540126": "è¾¾å­œåŽ¿",
	    "540127": "å¢¨ç«¹å·¥å¡åŽ¿",
	    "540128": "å…¶å®ƒåŒº",
	    "542100": "æ˜Œéƒ½åœ°åŒº",
	    "542121": "æ˜Œéƒ½åŽ¿",
	    "542122": "æ±Ÿè¾¾åŽ¿",
	    "542123": "è´¡è§‰åŽ¿",
	    "542124": "ç±»ä¹Œé½åŽ¿",
	    "542125": "ä¸é’åŽ¿",
	    "542126": "å¯Ÿé›…åŽ¿",
	    "542127": "å…«å®¿åŽ¿",
	    "542128": "å·¦è´¡åŽ¿",
	    "542129": "èŠ’åº·åŽ¿",
	    "542132": "æ´›éš†åŽ¿",
	    "542133": "è¾¹ååŽ¿",
	    "542134": "å…¶å®ƒåŒº",
	    "542200": "å±±å—åœ°åŒº",
	    "542221": "ä¹ƒä¸œåŽ¿",
	    "542222": "æ‰Žå›ŠåŽ¿",
	    "542223": "è´¡å˜ŽåŽ¿",
	    "542224": "æ¡‘æ—¥åŽ¿",
	    "542225": "ç¼ç»“åŽ¿",
	    "542226": "æ›²æ¾åŽ¿",
	    "542227": "æŽªç¾ŽåŽ¿",
	    "542228": "æ´›æ‰ŽåŽ¿",
	    "542229": "åŠ æŸ¥åŽ¿",
	    "542231": "éš†å­åŽ¿",
	    "542232": "é”™é‚£åŽ¿",
	    "542233": "æµªå¡å­åŽ¿",
	    "542234": "å…¶å®ƒåŒº",
	    "542300": "æ—¥å–€åˆ™åœ°åŒº",
	    "542301": "æ—¥å–€åˆ™å¸‚",
	    "542322": "å—æœ¨æž—åŽ¿",
	    "542323": "æ±Ÿå­œåŽ¿",
	    "542324": "å®šæ—¥åŽ¿",
	    "542325": "è¨è¿¦åŽ¿",
	    "542326": "æ‹‰å­œåŽ¿",
	    "542327": "æ˜‚ä»åŽ¿",
	    "542328": "è°¢é€šé—¨åŽ¿",
	    "542329": "ç™½æœ—åŽ¿",
	    "542330": "ä»å¸ƒåŽ¿",
	    "542331": "åº·é©¬åŽ¿",
	    "542332": "å®šç»“åŽ¿",
	    "542333": "ä»²å·´åŽ¿",
	    "542334": "äºšä¸œåŽ¿",
	    "542335": "å‰éš†åŽ¿",
	    "542336": "è‚æ‹‰æœ¨åŽ¿",
	    "542337": "è¨å˜ŽåŽ¿",
	    "542338": "å²—å·´åŽ¿",
	    "542339": "å…¶å®ƒåŒº",
	    "542400": "é‚£æ›²åœ°åŒº",
	    "542421": "é‚£æ›²åŽ¿",
	    "542422": "å˜‰é»ŽåŽ¿",
	    "542423": "æ¯”å¦‚åŽ¿",
	    "542424": "è‚è£åŽ¿",
	    "542425": "å®‰å¤šåŽ¿",
	    "542426": "ç”³æ‰ŽåŽ¿",
	    "542427": "ç´¢åŽ¿",
	    "542428": "ç­æˆˆåŽ¿",
	    "542429": "å·´é’åŽ¿",
	    "542430": "å°¼çŽ›åŽ¿",
	    "542431": "å…¶å®ƒåŒº",
	    "542432": "åŒæ¹–åŽ¿",
	    "542500": "é˜¿é‡Œåœ°åŒº",
	    "542521": "æ™®å…°åŽ¿",
	    "542522": "æœ­è¾¾åŽ¿",
	    "542523": "å™¶å°”åŽ¿",
	    "542524": "æ—¥åœŸåŽ¿",
	    "542525": "é©å‰åŽ¿",
	    "542526": "æ”¹åˆ™åŽ¿",
	    "542527": "æŽªå‹¤åŽ¿",
	    "542528": "å…¶å®ƒåŒº",
	    "542600": "æž—èŠåœ°åŒº",
	    "542621": "æž—èŠåŽ¿",
	    "542622": "å·¥å¸ƒæ±Ÿè¾¾åŽ¿",
	    "542623": "ç±³æž—åŽ¿",
	    "542624": "å¢¨è„±åŽ¿",
	    "542625": "æ³¢å¯†åŽ¿",
	    "542626": "å¯Ÿéš…åŽ¿",
	    "542627": "æœ—åŽ¿",
	    "542628": "å…¶å®ƒåŒº",
	    "610000": "é™•è¥¿çœ",
	    "610100": "è¥¿å®‰å¸‚",
	    "610102": "æ–°åŸŽåŒº",
	    "610103": "ç¢‘æž—åŒº",
	    "610104": "èŽ²æ¹–åŒº",
	    "610111": "çžæ¡¥åŒº",
	    "610112": "æœªå¤®åŒº",
	    "610113": "é›å¡”åŒº",
	    "610114": "é˜Žè‰¯åŒº",
	    "610115": "ä¸´æ½¼åŒº",
	    "610116": "é•¿å®‰åŒº",
	    "610122": "è“ç”°åŽ¿",
	    "610124": "å‘¨è‡³åŽ¿",
	    "610125": "æˆ·åŽ¿",
	    "610126": "é«˜é™µåŽ¿",
	    "610127": "å…¶å®ƒåŒº",
	    "610200": "é“œå·å¸‚",
	    "610202": "çŽ‹ç›ŠåŒº",
	    "610203": "å°å°åŒº",
	    "610204": "è€€å·žåŒº",
	    "610222": "å®œå›åŽ¿",
	    "610223": "å…¶å®ƒåŒº",
	    "610300": "å®é¸¡å¸‚",
	    "610302": "æ¸­æ»¨åŒº",
	    "610303": "é‡‘å°åŒº",
	    "610304": "é™ˆä»“åŒº",
	    "610322": "å‡¤ç¿”åŽ¿",
	    "610323": "å²å±±åŽ¿",
	    "610324": "æ‰¶é£ŽåŽ¿",
	    "610326": "çœ‰åŽ¿",
	    "610327": "é™‡åŽ¿",
	    "610328": "åƒé˜³åŽ¿",
	    "610329": "éºŸæ¸¸åŽ¿",
	    "610330": "å‡¤åŽ¿",
	    "610331": "å¤ªç™½åŽ¿",
	    "610332": "å…¶å®ƒåŒº",
	    "610400": "å’¸é˜³å¸‚",
	    "610402": "ç§¦éƒ½åŒº",
	    "610403": "æ¨é™µåŒº",
	    "610404": "æ¸­åŸŽåŒº",
	    "610422": "ä¸‰åŽŸåŽ¿",
	    "610423": "æ³¾é˜³åŽ¿",
	    "610424": "ä¹¾åŽ¿",
	    "610425": "ç¤¼æ³‰åŽ¿",
	    "610426": "æ°¸å¯¿åŽ¿",
	    "610427": "å½¬åŽ¿",
	    "610428": "é•¿æ­¦åŽ¿",
	    "610429": "æ—¬é‚‘åŽ¿",
	    "610430": "æ·³åŒ–åŽ¿",
	    "610431": "æ­¦åŠŸåŽ¿",
	    "610481": "å…´å¹³å¸‚",
	    "610482": "å…¶å®ƒåŒº",
	    "610500": "æ¸­å—å¸‚",
	    "610502": "ä¸´æ¸­åŒº",
	    "610521": "åŽåŽ¿",
	    "610522": "æ½¼å…³åŽ¿",
	    "610523": "å¤§è”åŽ¿",
	    "610524": "åˆé˜³åŽ¿",
	    "610525": "æ¾„åŸŽåŽ¿",
	    "610526": "è’²åŸŽåŽ¿",
	    "610527": "ç™½æ°´åŽ¿",
	    "610528": "å¯Œå¹³åŽ¿",
	    "610581": "éŸ©åŸŽå¸‚",
	    "610582": "åŽé˜´å¸‚",
	    "610583": "å…¶å®ƒåŒº",
	    "610600": "å»¶å®‰å¸‚",
	    "610602": "å®å¡”åŒº",
	    "610621": "å»¶é•¿åŽ¿",
	    "610622": "å»¶å·åŽ¿",
	    "610623": "å­é•¿åŽ¿",
	    "610624": "å®‰å¡žåŽ¿",
	    "610625": "å¿—ä¸¹åŽ¿",
	    "610626": "å´èµ·åŽ¿",
	    "610627": "ç”˜æ³‰åŽ¿",
	    "610628": "å¯ŒåŽ¿",
	    "610629": "æ´›å·åŽ¿",
	    "610630": "å®œå·åŽ¿",
	    "610631": "é»„é¾™åŽ¿",
	    "610632": "é»„é™µåŽ¿",
	    "610633": "å…¶å®ƒåŒº",
	    "610700": "æ±‰ä¸­å¸‚",
	    "610702": "æ±‰å°åŒº",
	    "610721": "å—éƒ‘åŽ¿",
	    "610722": "åŸŽå›ºåŽ¿",
	    "610723": "æ´‹åŽ¿",
	    "610724": "è¥¿ä¹¡åŽ¿",
	    "610725": "å‹‰åŽ¿",
	    "610726": "å®å¼ºåŽ¿",
	    "610727": "ç•¥é˜³åŽ¿",
	    "610728": "é•‡å·´åŽ¿",
	    "610729": "ç•™ååŽ¿",
	    "610730": "ä½›åªåŽ¿",
	    "610731": "å…¶å®ƒåŒº",
	    "610800": "æ¦†æž—å¸‚",
	    "610802": "æ¦†é˜³åŒº",
	    "610821": "ç¥žæœ¨åŽ¿",
	    "610822": "åºœè°·åŽ¿",
	    "610823": "æ¨ªå±±åŽ¿",
	    "610824": "é–è¾¹åŽ¿",
	    "610825": "å®šè¾¹åŽ¿",
	    "610826": "ç»¥å¾·åŽ¿",
	    "610827": "ç±³è„‚åŽ¿",
	    "610828": "ä½³åŽ¿",
	    "610829": "å´å ¡åŽ¿",
	    "610830": "æ¸…æ¶§åŽ¿",
	    "610831": "å­æ´²åŽ¿",
	    "610832": "å…¶å®ƒåŒº",
	    "610900": "å®‰åº·å¸‚",
	    "610902": "æ±‰æ»¨åŒº",
	    "610921": "æ±‰é˜´åŽ¿",
	    "610922": "çŸ³æ³‰åŽ¿",
	    "610923": "å®é™•åŽ¿",
	    "610924": "ç´«é˜³åŽ¿",
	    "610925": "å²šçš‹åŽ¿",
	    "610926": "å¹³åˆ©åŽ¿",
	    "610927": "é•‡åªåŽ¿",
	    "610928": "æ—¬é˜³åŽ¿",
	    "610929": "ç™½æ²³åŽ¿",
	    "610930": "å…¶å®ƒåŒº",
	    "611000": "å•†æ´›å¸‚",
	    "611002": "å•†å·žåŒº",
	    "611021": "æ´›å—åŽ¿",
	    "611022": "ä¸¹å‡¤åŽ¿",
	    "611023": "å•†å—åŽ¿",
	    "611024": "å±±é˜³åŽ¿",
	    "611025": "é•‡å®‰åŽ¿",
	    "611026": "æŸžæ°´åŽ¿",
	    "611027": "å…¶å®ƒåŒº",
	    "620000": "ç”˜è‚ƒçœ",
	    "620100": "å…°å·žå¸‚",
	    "620102": "åŸŽå…³åŒº",
	    "620103": "ä¸ƒé‡Œæ²³åŒº",
	    "620104": "è¥¿å›ºåŒº",
	    "620105": "å®‰å®åŒº",
	    "620111": "çº¢å¤åŒº",
	    "620121": "æ°¸ç™»åŽ¿",
	    "620122": "çš‹å…°åŽ¿",
	    "620123": "æ¦†ä¸­åŽ¿",
	    "620124": "å…¶å®ƒåŒº",
	    "620200": "å˜‰å³ªå…³å¸‚",
	    "620300": "é‡‘æ˜Œå¸‚",
	    "620302": "é‡‘å·åŒº",
	    "620321": "æ°¸æ˜ŒåŽ¿",
	    "620322": "å…¶å®ƒåŒº",
	    "620400": "ç™½é“¶å¸‚",
	    "620402": "ç™½é“¶åŒº",
	    "620403": "å¹³å·åŒº",
	    "620421": "é–è¿œåŽ¿",
	    "620422": "ä¼šå®åŽ¿",
	    "620423": "æ™¯æ³°åŽ¿",
	    "620424": "å…¶å®ƒåŒº",
	    "620500": "å¤©æ°´å¸‚",
	    "620502": "ç§¦å·žåŒº",
	    "620503": "éº¦ç§¯åŒº",
	    "620521": "æ¸…æ°´åŽ¿",
	    "620522": "ç§¦å®‰åŽ¿",
	    "620523": "ç”˜è°·åŽ¿",
	    "620524": "æ­¦å±±åŽ¿",
	    "620525": "å¼ å®¶å·å›žæ—è‡ªæ²»åŽ¿",
	    "620526": "å…¶å®ƒåŒº",
	    "620600": "æ­¦å¨å¸‚",
	    "620602": "å‡‰å·žåŒº",
	    "620621": "æ°‘å‹¤åŽ¿",
	    "620622": "å¤æµªåŽ¿",
	    "620623": "å¤©ç¥è—æ—è‡ªæ²»åŽ¿",
	    "620624": "å…¶å®ƒåŒº",
	    "620700": "å¼ æŽ–å¸‚",
	    "620702": "ç”˜å·žåŒº",
	    "620721": "è‚ƒå—è£•å›ºæ—è‡ªæ²»åŽ¿",
	    "620722": "æ°‘ä¹åŽ¿",
	    "620723": "ä¸´æ³½åŽ¿",
	    "620724": "é«˜å°åŽ¿",
	    "620725": "å±±ä¸¹åŽ¿",
	    "620726": "å…¶å®ƒåŒº",
	    "620800": "å¹³å‡‰å¸‚",
	    "620802": "å´†å³’åŒº",
	    "620821": "æ³¾å·åŽ¿",
	    "620822": "çµå°åŽ¿",
	    "620823": "å´‡ä¿¡åŽ¿",
	    "620824": "åŽäº­åŽ¿",
	    "620825": "åº„æµªåŽ¿",
	    "620826": "é™å®åŽ¿",
	    "620827": "å…¶å®ƒåŒº",
	    "620900": "é…’æ³‰å¸‚",
	    "620902": "è‚ƒå·žåŒº",
	    "620921": "é‡‘å¡”åŽ¿",
	    "620922": "ç“œå·žåŽ¿",
	    "620923": "è‚ƒåŒ—è’™å¤æ—è‡ªæ²»åŽ¿",
	    "620924": "é˜¿å…‹å¡žå“ˆè¨å…‹æ—è‡ªæ²»åŽ¿",
	    "620981": "çŽ‰é—¨å¸‚",
	    "620982": "æ•¦ç…Œå¸‚",
	    "620983": "å…¶å®ƒåŒº",
	    "621000": "åº†é˜³å¸‚",
	    "621002": "è¥¿å³°åŒº",
	    "621021": "åº†åŸŽåŽ¿",
	    "621022": "çŽ¯åŽ¿",
	    "621023": "åŽæ± åŽ¿",
	    "621024": "åˆæ°´åŽ¿",
	    "621025": "æ­£å®åŽ¿",
	    "621026": "å®åŽ¿",
	    "621027": "é•‡åŽŸåŽ¿",
	    "621028": "å…¶å®ƒåŒº",
	    "621100": "å®šè¥¿å¸‚",
	    "621102": "å®‰å®šåŒº",
	    "621121": "é€šæ¸­åŽ¿",
	    "621122": "é™‡è¥¿åŽ¿",
	    "621123": "æ¸­æºåŽ¿",
	    "621124": "ä¸´æ´®åŽ¿",
	    "621125": "æ¼³åŽ¿",
	    "621126": "å²·åŽ¿",
	    "621127": "å…¶å®ƒåŒº",
	    "621200": "é™‡å—å¸‚",
	    "621202": "æ­¦éƒ½åŒº",
	    "621221": "æˆåŽ¿",
	    "621222": "æ–‡åŽ¿",
	    "621223": "å®•æ˜ŒåŽ¿",
	    "621224": "åº·åŽ¿",
	    "621225": "è¥¿å’ŒåŽ¿",
	    "621226": "ç¤¼åŽ¿",
	    "621227": "å¾½åŽ¿",
	    "621228": "ä¸¤å½“åŽ¿",
	    "621229": "å…¶å®ƒåŒº",
	    "622900": "ä¸´å¤å›žæ—è‡ªæ²»å·ž",
	    "622901": "ä¸´å¤å¸‚",
	    "622921": "ä¸´å¤åŽ¿",
	    "622922": "åº·ä¹åŽ¿",
	    "622923": "æ°¸é–åŽ¿",
	    "622924": "å¹¿æ²³åŽ¿",
	    "622925": "å’Œæ”¿åŽ¿",
	    "622926": "ä¸œä¹¡æ—è‡ªæ²»åŽ¿",
	    "622927": "ç§¯çŸ³å±±ä¿å®‰æ—ä¸œä¹¡æ—æ’’æ‹‰æ—è‡ªæ²»åŽ¿",
	    "622928": "å…¶å®ƒåŒº",
	    "623000": "ç”˜å—è—æ—è‡ªæ²»å·ž",
	    "623001": "åˆä½œå¸‚",
	    "623021": "ä¸´æ½­åŽ¿",
	    "623022": "å“å°¼åŽ¿",
	    "623023": "èˆŸæ›²åŽ¿",
	    "623024": "è¿­éƒ¨åŽ¿",
	    "623025": "çŽ›æ›²åŽ¿",
	    "623026": "ç¢Œæ›²åŽ¿",
	    "623027": "å¤æ²³åŽ¿",
	    "623028": "å…¶å®ƒåŒº",
	    "630000": "é’æµ·çœ",
	    "630100": "è¥¿å®å¸‚",
	    "630102": "åŸŽä¸œåŒº",
	    "630103": "åŸŽä¸­åŒº",
	    "630104": "åŸŽè¥¿åŒº",
	    "630105": "åŸŽåŒ—åŒº",
	    "630121": "å¤§é€šå›žæ—åœŸæ—è‡ªæ²»åŽ¿",
	    "630122": "æ¹Ÿä¸­åŽ¿",
	    "630123": "æ¹ŸæºåŽ¿",
	    "630124": "å…¶å®ƒåŒº",
	    "632100": "æµ·ä¸œå¸‚",
	    "632121": "å¹³å®‰åŽ¿",
	    "632122": "æ°‘å’Œå›žæ—åœŸæ—è‡ªæ²»åŽ¿",
	    "632123": "ä¹éƒ½åŒº",
	    "632126": "äº’åŠ©åœŸæ—è‡ªæ²»åŽ¿",
	    "632127": "åŒ–éš†å›žæ—è‡ªæ²»åŽ¿",
	    "632128": "å¾ªåŒ–æ’’æ‹‰æ—è‡ªæ²»åŽ¿",
	    "632129": "å…¶å®ƒåŒº",
	    "632200": "æµ·åŒ—è—æ—è‡ªæ²»å·ž",
	    "632221": "é—¨æºå›žæ—è‡ªæ²»åŽ¿",
	    "632222": "ç¥è¿žåŽ¿",
	    "632223": "æµ·æ™åŽ¿",
	    "632224": "åˆšå¯ŸåŽ¿",
	    "632225": "å…¶å®ƒåŒº",
	    "632300": "é»„å—è—æ—è‡ªæ²»å·ž",
	    "632321": "åŒä»åŽ¿",
	    "632322": "å°–æ‰ŽåŽ¿",
	    "632323": "æ³½åº“åŽ¿",
	    "632324": "æ²³å—è’™å¤æ—è‡ªæ²»åŽ¿",
	    "632325": "å…¶å®ƒåŒº",
	    "632500": "æµ·å—è—æ—è‡ªæ²»å·ž",
	    "632521": "å…±å’ŒåŽ¿",
	    "632522": "åŒå¾·åŽ¿",
	    "632523": "è´µå¾·åŽ¿",
	    "632524": "å…´æµ·åŽ¿",
	    "632525": "è´µå—åŽ¿",
	    "632526": "å…¶å®ƒåŒº",
	    "632600": "æžœæ´›è—æ—è‡ªæ²»å·ž",
	    "632621": "çŽ›æ²åŽ¿",
	    "632622": "ç­çŽ›åŽ¿",
	    "632623": "ç”˜å¾·åŽ¿",
	    "632624": "è¾¾æ—¥åŽ¿",
	    "632625": "ä¹…æ²»åŽ¿",
	    "632626": "çŽ›å¤šåŽ¿",
	    "632627": "å…¶å®ƒåŒº",
	    "632700": "çŽ‰æ ‘è—æ—è‡ªæ²»å·ž",
	    "632721": "çŽ‰æ ‘å¸‚",
	    "632722": "æ‚å¤šåŽ¿",
	    "632723": "ç§°å¤šåŽ¿",
	    "632724": "æ²»å¤šåŽ¿",
	    "632725": "å›Šè°¦åŽ¿",
	    "632726": "æ›²éº»èŽ±åŽ¿",
	    "632727": "å…¶å®ƒåŒº",
	    "632800": "æµ·è¥¿è’™å¤æ—è—æ—è‡ªæ²»å·ž",
	    "632801": "æ ¼å°”æœ¨å¸‚",
	    "632802": "å¾·ä»¤å“ˆå¸‚",
	    "632821": "ä¹Œå…°åŽ¿",
	    "632822": "éƒ½å…°åŽ¿",
	    "632823": "å¤©å³»åŽ¿",
	    "632824": "å…¶å®ƒåŒº",
	    "640000": "å®å¤å›žæ—è‡ªæ²»åŒº",
	    "640100": "é“¶å·å¸‚",
	    "640104": "å…´åº†åŒº",
	    "640105": "è¥¿å¤åŒº",
	    "640106": "é‡‘å‡¤åŒº",
	    "640121": "æ°¸å®åŽ¿",
	    "640122": "è´ºå…°åŽ¿",
	    "640181": "çµæ­¦å¸‚",
	    "640182": "å…¶å®ƒåŒº",
	    "640200": "çŸ³å˜´å±±å¸‚",
	    "640202": "å¤§æ­¦å£åŒº",
	    "640205": "æƒ å†œåŒº",
	    "640221": "å¹³ç½—åŽ¿",
	    "640222": "å…¶å®ƒåŒº",
	    "640300": "å´å¿ å¸‚",
	    "640302": "åˆ©é€šåŒº",
	    "640303": "çº¢å¯ºå ¡åŒº",
	    "640323": "ç›æ± åŽ¿",
	    "640324": "åŒå¿ƒåŽ¿",
	    "640381": "é’é“œå³¡å¸‚",
	    "640382": "å…¶å®ƒåŒº",
	    "640400": "å›ºåŽŸå¸‚",
	    "640402": "åŽŸå·žåŒº",
	    "640422": "è¥¿å‰åŽ¿",
	    "640423": "éš†å¾·åŽ¿",
	    "640424": "æ³¾æºåŽ¿",
	    "640425": "å½­é˜³åŽ¿",
	    "640426": "å…¶å®ƒåŒº",
	    "640500": "ä¸­å«å¸‚",
	    "640502": "æ²™å¡å¤´åŒº",
	    "640521": "ä¸­å®åŽ¿",
	    "640522": "æµ·åŽŸåŽ¿",
	    "640523": "å…¶å®ƒåŒº",
	    "650000": "æ–°ç–†ç»´å¾å°”è‡ªæ²»åŒº",
	    "650100": "ä¹Œé²æœ¨é½å¸‚",
	    "650102": "å¤©å±±åŒº",
	    "650103": "æ²™ä¾å·´å…‹åŒº",
	    "650104": "æ–°å¸‚åŒº",
	    "650105": "æ°´ç£¨æ²ŸåŒº",
	    "650106": "å¤´å±¯æ²³åŒº",
	    "650107": "è¾¾å‚åŸŽåŒº",
	    "650109": "ç±³ä¸œåŒº",
	    "650121": "ä¹Œé²æœ¨é½åŽ¿",
	    "650122": "å…¶å®ƒåŒº",
	    "650200": "å…‹æ‹‰çŽ›ä¾å¸‚",
	    "650202": "ç‹¬å±±å­åŒº",
	    "650203": "å…‹æ‹‰çŽ›ä¾åŒº",
	    "650204": "ç™½ç¢±æ»©åŒº",
	    "650205": "ä¹Œå°”ç¦¾åŒº",
	    "650206": "å…¶å®ƒåŒº",
	    "652100": "åé²ç•ªåœ°åŒº",
	    "652101": "åé²ç•ªå¸‚",
	    "652122": "é„¯å–„åŽ¿",
	    "652123": "æ‰˜å…‹é€ŠåŽ¿",
	    "652124": "å…¶å®ƒåŒº",
	    "652200": "å“ˆå¯†åœ°åŒº",
	    "652201": "å“ˆå¯†å¸‚",
	    "652222": "å·´é‡Œå¤å“ˆè¨å…‹è‡ªæ²»åŽ¿",
	    "652223": "ä¼Šå¾åŽ¿",
	    "652224": "å…¶å®ƒåŒº",
	    "652300": "æ˜Œå‰å›žæ—è‡ªæ²»å·ž",
	    "652301": "æ˜Œå‰å¸‚",
	    "652302": "é˜œåº·å¸‚",
	    "652323": "å‘¼å›¾å£åŽ¿",
	    "652324": "çŽ›çº³æ–¯åŽ¿",
	    "652325": "å¥‡å°åŽ¿",
	    "652327": "å‰æœ¨è¨å°”åŽ¿",
	    "652328": "æœ¨åž’å“ˆè¨å…‹è‡ªæ²»åŽ¿",
	    "652329": "å…¶å®ƒåŒº",
	    "652700": "åšå°”å¡”æ‹‰è’™å¤è‡ªæ²»å·ž",
	    "652701": "åšä¹å¸‚",
	    "652702": "é˜¿æ‹‰å±±å£å¸‚",
	    "652722": "ç²¾æ²³åŽ¿",
	    "652723": "æ¸©æ³‰åŽ¿",
	    "652724": "å…¶å®ƒåŒº",
	    "652800": "å·´éŸ³éƒ­æ¥žè’™å¤è‡ªæ²»å·ž",
	    "652801": "åº“å°”å‹’å¸‚",
	    "652822": "è½®å°åŽ¿",
	    "652823": "å°‰çŠåŽ¿",
	    "652824": "è‹¥ç¾ŒåŽ¿",
	    "652825": "ä¸”æœ«åŽ¿",
	    "652826": "ç„‰è€†å›žæ—è‡ªæ²»åŽ¿",
	    "652827": "å’Œé™åŽ¿",
	    "652828": "å’Œç¡•åŽ¿",
	    "652829": "åšæ¹–åŽ¿",
	    "652830": "å…¶å®ƒåŒº",
	    "652900": "é˜¿å…‹è‹åœ°åŒº",
	    "652901": "é˜¿å…‹è‹å¸‚",
	    "652922": "æ¸©å®¿åŽ¿",
	    "652923": "åº“è½¦åŽ¿",
	    "652924": "æ²™é›…åŽ¿",
	    "652925": "æ–°å’ŒåŽ¿",
	    "652926": "æ‹œåŸŽåŽ¿",
	    "652927": "ä¹Œä»€åŽ¿",
	    "652928": "é˜¿ç“¦æåŽ¿",
	    "652929": "æŸ¯åªåŽ¿",
	    "652930": "å…¶å®ƒåŒº",
	    "653000": "å…‹å­œå‹’è‹æŸ¯å°”å…‹å­œè‡ªæ²»å·ž",
	    "653001": "é˜¿å›¾ä»€å¸‚",
	    "653022": "é˜¿å…‹é™¶åŽ¿",
	    "653023": "é˜¿åˆå¥‡åŽ¿",
	    "653024": "ä¹Œæ°åŽ¿",
	    "653025": "å…¶å®ƒåŒº",
	    "653100": "å–€ä»€åœ°åŒº",
	    "653101": "å–€ä»€å¸‚",
	    "653121": "ç–é™„åŽ¿",
	    "653122": "ç–å‹’åŽ¿",
	    "653123": "è‹±å‰æ²™åŽ¿",
	    "653124": "æ³½æ™®åŽ¿",
	    "653125": "èŽŽè½¦åŽ¿",
	    "653126": "å¶åŸŽåŽ¿",
	    "653127": "éº¦ç›–æåŽ¿",
	    "653128": "å²³æ™®æ¹–åŽ¿",
	    "653129": "ä¼½å¸ˆåŽ¿",
	    "653130": "å·´æ¥šåŽ¿",
	    "653131": "å¡”ä»€åº“å°”å¹²å¡”å‰å…‹è‡ªæ²»åŽ¿",
	    "653132": "å…¶å®ƒåŒº",
	    "653200": "å’Œç”°åœ°åŒº",
	    "653201": "å’Œç”°å¸‚",
	    "653221": "å’Œç”°åŽ¿",
	    "653222": "å¢¨çŽ‰åŽ¿",
	    "653223": "çš®å±±åŽ¿",
	    "653224": "æ´›æµ¦åŽ¿",
	    "653225": "ç­–å‹’åŽ¿",
	    "653226": "äºŽç”°åŽ¿",
	    "653227": "æ°‘ä¸°åŽ¿",
	    "653228": "å…¶å®ƒåŒº",
	    "654000": "ä¼ŠçŠå“ˆè¨å…‹è‡ªæ²»å·ž",
	    "654002": "ä¼Šå®å¸‚",
	    "654003": "å¥Žå±¯å¸‚",
	    "654021": "ä¼Šå®åŽ¿",
	    "654022": "å¯Ÿå¸ƒæŸ¥å°”é”¡ä¼¯è‡ªæ²»åŽ¿",
	    "654023": "éœåŸŽåŽ¿",
	    "654024": "å·©ç•™åŽ¿",
	    "654025": "æ–°æºåŽ¿",
	    "654026": "æ˜­è‹åŽ¿",
	    "654027": "ç‰¹å…‹æ–¯åŽ¿",
	    "654028": "å°¼å‹’å…‹åŽ¿",
	    "654029": "å…¶å®ƒåŒº",
	    "654200": "å¡”åŸŽåœ°åŒº",
	    "654201": "å¡”åŸŽå¸‚",
	    "654202": "ä¹Œè‹å¸‚",
	    "654221": "é¢æ•åŽ¿",
	    "654223": "æ²™æ¹¾åŽ¿",
	    "654224": "æ‰˜é‡ŒåŽ¿",
	    "654225": "è£•æ°‘åŽ¿",
	    "654226": "å’Œå¸ƒå…‹èµ›å°”è’™å¤è‡ªæ²»åŽ¿",
	    "654227": "å…¶å®ƒåŒº",
	    "654300": "é˜¿å‹’æ³°åœ°åŒº",
	    "654301": "é˜¿å‹’æ³°å¸‚",
	    "654321": "å¸ƒå°”æ´¥åŽ¿",
	    "654322": "å¯Œè•´åŽ¿",
	    "654323": "ç¦æµ·åŽ¿",
	    "654324": "å“ˆå·´æ²³åŽ¿",
	    "654325": "é’æ²³åŽ¿",
	    "654326": "å‰æœ¨ä¹ƒåŽ¿",
	    "654327": "å…¶å®ƒåŒº",
	    "659001": "çŸ³æ²³å­å¸‚",
	    "659002": "é˜¿æ‹‰å°”å¸‚",
	    "659003": "å›¾æœ¨èˆ’å…‹å¸‚",
	    "659004": "äº”å®¶æ¸ å¸‚",
	    "710000": "å°æ¹¾",
	    "710100": "å°åŒ—å¸‚",
	    "710101": "ä¸­æ­£åŒº",
	    "710102": "å¤§åŒåŒº",
	    "710103": "ä¸­å±±åŒº",
	    "710104": "æ¾å±±åŒº",
	    "710105": "å¤§å®‰åŒº",
	    "710106": "ä¸‡åŽåŒº",
	    "710107": "ä¿¡ä¹‰åŒº",
	    "710108": "å£«æž—åŒº",
	    "710109": "åŒ—æŠ•åŒº",
	    "710110": "å†…æ¹–åŒº",
	    "710111": "å—æ¸¯åŒº",
	    "710112": "æ–‡å±±åŒº",
	    "710113": "å…¶å®ƒåŒº",
	    "710200": "é«˜é›„å¸‚",
	    "710201": "æ–°å…´åŒº",
	    "710202": "å‰é‡‘åŒº",
	    "710203": "èŠ©é›…åŒº",
	    "710204": "ç›åŸ•åŒº",
	    "710205": "é¼“å±±åŒº",
	    "710206": "æ——æ´¥åŒº",
	    "710207": "å‰é•‡åŒº",
	    "710208": "ä¸‰æ°‘åŒº",
	    "710209": "å·¦è¥åŒº",
	    "710210": "æ¥ æ¢“åŒº",
	    "710211": "å°æ¸¯åŒº",
	    "710212": "å…¶å®ƒåŒº",
	    "710241": "è‹“é›…åŒº",
	    "710242": "ä»æ­¦åŒº",
	    "710243": "å¤§ç¤¾åŒº",
	    "710244": "å†ˆå±±åŒº",
	    "710245": "è·¯ç«¹åŒº",
	    "710246": "é˜¿èŽ²åŒº",
	    "710247": "ç”°å¯®åŒº",
	    "710248": "ç‡•å·¢åŒº",
	    "710249": "æ¡¥å¤´åŒº",
	    "710250": "æ¢“å®˜åŒº",
	    "710251": "å¼¥é™€åŒº",
	    "710252": "æ°¸å®‰åŒº",
	    "710253": "æ¹–å†…åŒº",
	    "710254": "å‡¤å±±åŒº",
	    "710255": "å¤§å¯®åŒº",
	    "710256": "æž—å›­åŒº",
	    "710257": "é¸Ÿæ¾åŒº",
	    "710258": "å¤§æ ‘åŒº",
	    "710259": "æ——å±±åŒº",
	    "710260": "ç¾Žæµ“åŒº",
	    "710261": "å…­é¾ŸåŒº",
	    "710262": "å†…é—¨åŒº",
	    "710263": "æ‰æž—åŒº",
	    "710264": "ç”²ä»™åŒº",
	    "710265": "æ¡ƒæºåŒº",
	    "710266": "é‚£çŽ›å¤åŒº",
	    "710267": "èŒ‚æž—åŒº",
	    "710268": "èŒ„è£åŒº",
	    "710300": "å°å—å¸‚",
	    "710301": "ä¸­è¥¿åŒº",
	    "710302": "ä¸œåŒº",
	    "710303": "å—åŒº",
	    "710304": "åŒ—åŒº",
	    "710305": "å®‰å¹³åŒº",
	    "710306": "å®‰å—åŒº",
	    "710307": "å…¶å®ƒåŒº",
	    "710339": "æ°¸åº·åŒº",
	    "710340": "å½’ä»åŒº",
	    "710341": "æ–°åŒ–åŒº",
	    "710342": "å·¦é•‡åŒº",
	    "710343": "çŽ‰äº•åŒº",
	    "710344": "æ¥ è¥¿åŒº",
	    "710345": "å—åŒ–åŒº",
	    "710346": "ä»å¾·åŒº",
	    "710347": "å…³åº™åŒº",
	    "710348": "é¾™å´ŽåŒº",
	    "710349": "å®˜ç”°åŒº",
	    "710350": "éº»è±†åŒº",
	    "710351": "ä½³é‡ŒåŒº",
	    "710352": "è¥¿æ¸¯åŒº",
	    "710353": "ä¸ƒè‚¡åŒº",
	    "710354": "å°†å†›åŒº",
	    "710355": "å­¦ç”²åŒº",
	    "710356": "åŒ—é—¨åŒº",
	    "710357": "æ–°è¥åŒº",
	    "710358": "åŽå£åŒº",
	    "710359": "ç™½æ²³åŒº",
	    "710360": "ä¸œå±±åŒº",
	    "710361": "å…­ç”²åŒº",
	    "710362": "ä¸‹è¥åŒº",
	    "710363": "æŸ³è¥åŒº",
	    "710364": "ç›æ°´åŒº",
	    "710365": "å–„åŒ–åŒº",
	    "710366": "å¤§å†…åŒº",
	    "710367": "å±±ä¸ŠåŒº",
	    "710368": "æ–°å¸‚åŒº",
	    "710369": "å®‰å®šåŒº",
	    "710400": "å°ä¸­å¸‚",
	    "710401": "ä¸­åŒº",
	    "710402": "ä¸œåŒº",
	    "710403": "å—åŒº",
	    "710404": "è¥¿åŒº",
	    "710405": "åŒ—åŒº",
	    "710406": "åŒ—å±¯åŒº",
	    "710407": "è¥¿å±¯åŒº",
	    "710408": "å—å±¯åŒº",
	    "710409": "å…¶å®ƒåŒº",
	    "710431": "å¤ªå¹³åŒº",
	    "710432": "å¤§é‡ŒåŒº",
	    "710433": "é›¾å³°åŒº",
	    "710434": "ä¹Œæ—¥åŒº",
	    "710435": "ä¸°åŽŸåŒº",
	    "710436": "åŽé‡ŒåŒº",
	    "710437": "çŸ³å†ˆåŒº",
	    "710438": "ä¸œåŠ¿åŒº",
	    "710439": "å’Œå¹³åŒº",
	    "710440": "æ–°ç¤¾åŒº",
	    "710441": "æ½­å­åŒº",
	    "710442": "å¤§é›…åŒº",
	    "710443": "ç¥žå†ˆåŒº",
	    "710444": "å¤§è‚šåŒº",
	    "710445": "æ²™é¹¿åŒº",
	    "710446": "é¾™äº•åŒº",
	    "710447": "æ¢§æ –åŒº",
	    "710448": "æ¸…æ°´åŒº",
	    "710449": "å¤§ç”²åŒº",
	    "710450": "å¤–åŸ”åŒº",
	    "710451": "å¤§å®‰åŒº",
	    "710500": "é‡‘é—¨åŽ¿",
	    "710507": "é‡‘æ²™é•‡",
	    "710508": "é‡‘æ¹–é•‡",
	    "710509": "é‡‘å®ä¹¡",
	    "710510": "é‡‘åŸŽé•‡",
	    "710511": "çƒˆå±¿ä¹¡",
	    "710512": "ä¹Œåµä¹¡",
	    "710600": "å—æŠ•åŽ¿",
	    "710614": "å—æŠ•å¸‚",
	    "710615": "ä¸­å¯®ä¹¡",
	    "710616": "è‰å±¯é•‡",
	    "710617": "å›½å§“ä¹¡",
	    "710618": "åŸ”é‡Œé•‡",
	    "710619": "ä»çˆ±ä¹¡",
	    "710620": "åé—´ä¹¡",
	    "710621": "é›†é›†é•‡",
	    "710622": "æ°´é‡Œä¹¡",
	    "710623": "é±¼æ± ä¹¡",
	    "710624": "ä¿¡ä¹‰ä¹¡",
	    "710625": "ç«¹å±±é•‡",
	    "710626": "é¹¿è°·ä¹¡",
	    "710700": "åŸºéš†å¸‚",
	    "710701": "ä»çˆ±åŒº",
	    "710702": "ä¿¡ä¹‰åŒº",
	    "710703": "ä¸­æ­£åŒº",
	    "710704": "ä¸­å±±åŒº",
	    "710705": "å®‰ä¹åŒº",
	    "710706": "æš–æš–åŒº",
	    "710707": "ä¸ƒå µåŒº",
	    "710708": "å…¶å®ƒåŒº",
	    "710800": "æ–°ç«¹å¸‚",
	    "710801": "ä¸œåŒº",
	    "710802": "åŒ—åŒº",
	    "710803": "é¦™å±±åŒº",
	    "710804": "å…¶å®ƒåŒº",
	    "710900": "å˜‰ä¹‰å¸‚",
	    "710901": "ä¸œåŒº",
	    "710902": "è¥¿åŒº",
	    "710903": "å…¶å®ƒåŒº",
	    "711100": "æ–°åŒ—å¸‚",
	    "711130": "ä¸‡é‡ŒåŒº",
	    "711131": "é‡‘å±±åŒº",
	    "711132": "æ¿æ¡¥åŒº",
	    "711133": "æ±æ­¢åŒº",
	    "711134": "æ·±å‘åŒº",
	    "711135": "çŸ³ç¢‡åŒº",
	    "711136": "ç‘žèŠ³åŒº",
	    "711137": "å¹³æºªåŒº",
	    "711138": "åŒæºªåŒº",
	    "711139": "è´¡å¯®åŒº",
	    "711140": "æ–°åº—åŒº",
	    "711141": "åªæž—åŒº",
	    "711142": "ä¹Œæ¥åŒº",
	    "711143": "æ°¸å’ŒåŒº",
	    "711144": "ä¸­å’ŒåŒº",
	    "711145": "åœŸåŸŽåŒº",
	    "711146": "ä¸‰å³¡åŒº",
	    "711147": "æ ‘æž—åŒº",
	    "711148": "èŽºæ­ŒåŒº",
	    "711149": "ä¸‰é‡åŒº",
	    "711150": "æ–°åº„åŒº",
	    "711151": "æ³°å±±åŒº",
	    "711152": "æž—å£åŒº",
	    "711153": "èŠ¦æ´²åŒº",
	    "711154": "äº”è‚¡åŒº",
	    "711155": "å…«é‡ŒåŒº",
	    "711156": "æ·¡æ°´åŒº",
	    "711157": "ä¸‰èŠåŒº",
	    "711158": "çŸ³é—¨åŒº",
	    "711200": "å®œå…°åŽ¿",
	    "711214": "å®œå…°å¸‚",
	    "711215": "å¤´åŸŽé•‡",
	    "711216": "ç¤æºªä¹¡",
	    "711217": "å£®å›´ä¹¡",
	    "711218": "å‘˜å±±ä¹¡",
	    "711219": "ç½—ä¸œé•‡",
	    "711220": "ä¸‰æ˜Ÿä¹¡",
	    "711221": "å¤§åŒä¹¡",
	    "711222": "äº”ç»“ä¹¡",
	    "711223": "å†¬å±±ä¹¡",
	    "711224": "è‹æ¾³é•‡",
	    "711225": "å—æ¾³ä¹¡",
	    "711226": "é’“é±¼å°",
	    "711300": "æ–°ç«¹åŽ¿",
	    "711314": "ç«¹åŒ—å¸‚",
	    "711315": "æ¹–å£ä¹¡",
	    "711316": "æ–°ä¸°ä¹¡",
	    "711317": "æ–°åŸ”é•‡",
	    "711318": "å…³è¥¿é•‡",
	    "711319": "èŠŽæž—ä¹¡",
	    "711320": "å®å±±ä¹¡",
	    "711321": "ç«¹ä¸œé•‡",
	    "711322": "äº”å³°ä¹¡",
	    "711323": "æ¨ªå±±ä¹¡",
	    "711324": "å°–çŸ³ä¹¡",
	    "711325": "åŒ—åŸ”ä¹¡",
	    "711326": "å³¨çœ‰ä¹¡",
	    "711400": "æ¡ƒå›­åŽ¿",
	    "711414": "ä¸­åœå¸‚",
	    "711415": "å¹³é•‡å¸‚",
	    "711416": "é¾™æ½­ä¹¡",
	    "711417": "æ¨æ¢…å¸‚",
	    "711418": "æ–°å±‹ä¹¡",
	    "711419": "è§‚éŸ³ä¹¡",
	    "711420": "æ¡ƒå›­å¸‚",
	    "711421": "é¾Ÿå±±ä¹¡",
	    "711422": "å…«å¾·å¸‚",
	    "711423": "å¤§æºªé•‡",
	    "711424": "å¤å…´ä¹¡",
	    "711425": "å¤§å›­ä¹¡",
	    "711426": "èŠ¦ç«¹ä¹¡",
	    "711500": "è‹—æ —åŽ¿",
	    "711519": "ç«¹å—é•‡",
	    "711520": "å¤´ä»½é•‡",
	    "711521": "ä¸‰æ¹¾ä¹¡",
	    "711522": "å—åº„ä¹¡",
	    "711523": "ç‹®æ½­ä¹¡",
	    "711524": "åŽé¾™é•‡",
	    "711525": "é€šéœ„é•‡",
	    "711526": "è‹‘é‡Œé•‡",
	    "711527": "è‹—æ —å¸‚",
	    "711528": "é€ æ¡¥ä¹¡",
	    "711529": "å¤´å±‹ä¹¡",
	    "711530": "å…¬é¦†ä¹¡",
	    "711531": "å¤§æ¹–ä¹¡",
	    "711532": "æ³°å®‰ä¹¡",
	    "711533": "é“œé”£ä¹¡",
	    "711534": "ä¸‰ä¹‰ä¹¡",
	    "711535": "è¥¿æ¹–ä¹¡",
	    "711536": "å“å…°é•‡",
	    "711700": "å½°åŒ–åŽ¿",
	    "711727": "å½°åŒ–å¸‚",
	    "711728": "èŠ¬å›­ä¹¡",
	    "711729": "èŠ±å›ä¹¡",
	    "711730": "ç§€æ°´ä¹¡",
	    "711731": "é¹¿æ¸¯é•‡",
	    "711732": "ç¦å…´ä¹¡",
	    "711733": "çº¿è¥¿ä¹¡",
	    "711734": "å’Œç¾Žé•‡",
	    "711735": "ä¼¸æ¸¯ä¹¡",
	    "711736": "å‘˜æž—é•‡",
	    "711737": "ç¤¾å¤´ä¹¡",
	    "711738": "æ°¸é–ä¹¡",
	    "711739": "åŸ”å¿ƒä¹¡",
	    "711740": "æºªæ¹–é•‡",
	    "711741": "å¤§æ‘ä¹¡",
	    "711742": "åŸ”ç›ä¹¡",
	    "711743": "ç”°ä¸­é•‡",
	    "711744": "åŒ—æ–—é•‡",
	    "711745": "ç”°å°¾ä¹¡",
	    "711746": "åŸ¤å¤´ä¹¡",
	    "711747": "æºªå·žä¹¡",
	    "711748": "ç«¹å¡˜ä¹¡",
	    "711749": "äºŒæž—é•‡",
	    "711750": "å¤§åŸŽä¹¡",
	    "711751": "èŠ³è‹‘ä¹¡",
	    "711752": "äºŒæ°´ä¹¡",
	    "711900": "å˜‰ä¹‰åŽ¿",
	    "711919": "ç•ªè·¯ä¹¡",
	    "711920": "æ¢…å±±ä¹¡",
	    "711921": "ç«¹å´Žä¹¡",
	    "711922": "é˜¿é‡Œå±±ä¹¡",
	    "711923": "ä¸­åŸ”ä¹¡",
	    "711924": "å¤§åŸ”ä¹¡",
	    "711925": "æ°´ä¸Šä¹¡",
	    "711926": "é¹¿è‰ä¹¡",
	    "711927": "å¤ªä¿å¸‚",
	    "711928": "æœ´å­å¸‚",
	    "711929": "ä¸œçŸ³ä¹¡",
	    "711930": "å…­è„šä¹¡",
	    "711931": "æ–°æ¸¯ä¹¡",
	    "711932": "æ°‘é›„ä¹¡",
	    "711933": "å¤§æž—é•‡",
	    "711934": "æºªå£ä¹¡",
	    "711935": "ä¹‰ç«¹ä¹¡",
	    "711936": "å¸ƒè¢‹é•‡",
	    "712100": "äº‘æž—åŽ¿",
	    "712121": "æ–—å—é•‡",
	    "712122": "å¤§åŸ¤ä¹¡",
	    "712123": "è™Žå°¾é•‡",
	    "712124": "åœŸåº“é•‡",
	    "712125": "è¤’å¿ ä¹¡",
	    "712126": "ä¸œåŠ¿ä¹¡",
	    "712127": "å°è¥¿ä¹¡",
	    "712128": "ä»‘èƒŒä¹¡",
	    "712129": "éº¦å¯®ä¹¡",
	    "712130": "æ–—å…­å¸‚",
	    "712131": "æž—å†…ä¹¡",
	    "712132": "å¤å‘ä¹¡",
	    "712133": "èŽ¿æ¡ä¹¡",
	    "712134": "è¥¿èžºé•‡",
	    "712135": "äºŒä»‘ä¹¡",
	    "712136": "åŒ—æ¸¯é•‡",
	    "712137": "æ°´æž—ä¹¡",
	    "712138": "å£æ¹–ä¹¡",
	    "712139": "å››æ¹–ä¹¡",
	    "712140": "å…ƒé•¿ä¹¡",
	    "712400": "å±ä¸œåŽ¿",
	    "712434": "å±ä¸œå¸‚",
	    "712435": "ä¸‰åœ°é—¨ä¹¡",
	    "712436": "é›¾å°ä¹¡",
	    "712437": "çŽ›å®¶ä¹¡",
	    "712438": "ä¹å¦‚ä¹¡",
	    "712439": "é‡Œæ¸¯ä¹¡",
	    "712440": "é«˜æ ‘ä¹¡",
	    "712441": "ç›åŸ”ä¹¡",
	    "712442": "é•¿æ²»ä¹¡",
	    "712443": "éºŸæ´›ä¹¡",
	    "712444": "ç«¹ç”°ä¹¡",
	    "712445": "å†…åŸ”ä¹¡",
	    "712446": "ä¸‡ä¸¹ä¹¡",
	    "712447": "æ½®å·žé•‡",
	    "712448": "æ³°æ­¦ä¹¡",
	    "712449": "æ¥ä¹‰ä¹¡",
	    "712450": "ä¸‡å³¦ä¹¡",
	    "712451": "å´é¡¶ä¹¡",
	    "712452": "æ–°åŸ¤ä¹¡",
	    "712453": "å—å·žä¹¡",
	    "712454": "æž—è¾¹ä¹¡",
	    "712455": "ä¸œæ¸¯é•‡",
	    "712456": "ç‰çƒä¹¡",
	    "712457": "ä½³å†¬ä¹¡",
	    "712458": "æ–°å›­ä¹¡",
	    "712459": "æž‹å¯®ä¹¡",
	    "712460": "æž‹å±±ä¹¡",
	    "712461": "æ˜¥æ—¥ä¹¡",
	    "712462": "ç‹®å­ä¹¡",
	    "712463": "è½¦åŸŽä¹¡",
	    "712464": "ç‰¡ä¸¹ä¹¡",
	    "712465": "æ’æ˜¥é•‡",
	    "712466": "æ»¡å·žä¹¡",
	    "712500": "å°ä¸œåŽ¿",
	    "712517": "å°ä¸œå¸‚",
	    "712518": "ç»¿å²›ä¹¡",
	    "712519": "å…°å±¿ä¹¡",
	    "712520": "å»¶å¹³ä¹¡",
	    "712521": "å‘å—ä¹¡",
	    "712522": "é¹¿é‡Žä¹¡",
	    "712523": "å…³å±±é•‡",
	    "712524": "æµ·ç«¯ä¹¡",
	    "712525": "æ± ä¸Šä¹¡",
	    "712526": "ä¸œæ²³ä¹¡",
	    "712527": "æˆåŠŸé•‡",
	    "712528": "é•¿æ»¨ä¹¡",
	    "712529": "é‡‘å³°ä¹¡",
	    "712530": "å¤§æ­¦ä¹¡",
	    "712531": "è¾¾ä»ä¹¡",
	    "712532": "å¤ªéº»é‡Œä¹¡",
	    "712600": "èŠ±èŽ²åŽ¿",
	    "712615": "èŠ±èŽ²å¸‚",
	    "712616": "æ–°åŸŽä¹¡",
	    "712617": "å¤ªé²é˜",
	    "712618": "ç§€æž—ä¹¡",
	    "712619": "å‰å®‰ä¹¡",
	    "712620": "å¯¿ä¸°ä¹¡",
	    "712621": "å‡¤æž—é•‡",
	    "712622": "å…‰å¤ä¹¡",
	    "712623": "ä¸°æ»¨ä¹¡",
	    "712624": "ç‘žç©—ä¹¡",
	    "712625": "ä¸‡è£ä¹¡",
	    "712626": "çŽ‰é‡Œé•‡",
	    "712627": "å“æºªä¹¡",
	    "712628": "å¯Œé‡Œä¹¡",
	    "712700": "æ¾Žæ¹–åŽ¿",
	    "712707": "é©¬å…¬å¸‚",
	    "712708": "è¥¿å±¿ä¹¡",
	    "712709": "æœ›å®‰ä¹¡",
	    "712710": "ä¸ƒç¾Žä¹¡",
	    "712711": "ç™½æ²™ä¹¡",
	    "712712": "æ¹–è¥¿ä¹¡",
	    "712800": "è¿žæ±ŸåŽ¿",
	    "712805": "å—ç«¿ä¹¡",
	    "712806": "åŒ—ç«¿ä¹¡",
	    "712807": "èŽ’å…‰ä¹¡",
	    "712808": "ä¸œå¼•ä¹¡",
	    "810000": "é¦™æ¸¯ç‰¹åˆ«è¡Œæ”¿åŒº",
	    "810100": "é¦™æ¸¯å²›",
	    "810101": "ä¸­è¥¿åŒº",
	    "810102": "æ¹¾ä»”",
	    "810103": "ä¸œåŒº",
	    "810104": "å—åŒº",
	    "810200": "ä¹é¾™",
	    "810201": "ä¹é¾™åŸŽåŒº",
	    "810202": "æ²¹å°–æ—ºåŒº",
	    "810203": "æ·±æ°´åŸ—åŒº",
	    "810204": "é»„å¤§ä»™åŒº",
	    "810205": "è§‚å¡˜åŒº",
	    "810300": "æ–°ç•Œ",
	    "810301": "åŒ—åŒº",
	    "810302": "å¤§åŸ”åŒº",
	    "810303": "æ²™ç”°åŒº",
	    "810304": "è¥¿è´¡åŒº",
	    "810305": "å…ƒæœ—åŒº",
	    "810306": "å±¯é—¨åŒº",
	    "810307": "èƒæ¹¾åŒº",
	    "810308": "è‘µé’åŒº",
	    "810309": "ç¦»å²›åŒº",
	    "820000": "æ¾³é—¨ç‰¹åˆ«è¡Œæ”¿åŒº",
	    "820100": "æ¾³é—¨åŠå²›",
	    "820200": "ç¦»å²›",
	    "990000": "æµ·å¤–",
	    "990100": "æµ·å¤–"
	}

	// id pid/parentId name children
	function tree(list) {
	    var mapped = {}
	    for (var i = 0, item; i < list.length; i++) {
	        item = list[i]
	        if (!item || !item.id) continue
	        mapped[item.id] = item
	    }

	    var result = []
	    for (var ii = 0; ii < list.length; ii++) {
	        item = list[ii]

	        if (!item) continue
	            /* jshint -W041 */
	        if (item.pid == undefined && item.parentId == undefined) {
	            result.push(item)
	            continue
	        }
	        var parent = mapped[item.pid] || mapped[item.parentId]
	        if (!parent) continue
	        if (!parent.children) parent.children = []
	        parent.children.push(item)
	    }
	    return result
	}

	var DICT_FIXED = function() {
	    var fixed = []
	    for (var id in DICT) {
	        var pid = id.slice(2, 6) === '0000' ? undefined :
	            id.slice(4, 6) == '00' ? (id.slice(0, 2) + '0000') :
	            id.slice(0, 4) + '00'
	        fixed.push({
	            id: id,
	            pid: pid,
	            name: DICT[id]
	        })
	    }
	    return tree(fixed)
	}()

	module.exports = DICT_FIXED

/***/ },
/* 19 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## Miscellaneous
	*/
	var DICT = __webpack_require__(18)
	module.exports = {
		// Dice
		d4: function() {
			return this.natural(1, 4)
		},
		d6: function() {
			return this.natural(1, 6)
		},
		d8: function() {
			return this.natural(1, 8)
		},
		d12: function() {
			return this.natural(1, 12)
		},
		d20: function() {
			return this.natural(1, 20)
		},
		d100: function() {
			return this.natural(1, 100)
		},
		/*
		    éšæœºç”Ÿæˆä¸€ä¸ª GUIDã€‚

		    http://www.broofa.com/2008/09/javascript-uuid-function/
		    [UUID è§„èŒƒ](http://www.ietf.org/rfc/rfc4122.txt)
		        UUIDs (Universally Unique IDentifier)
		        GUIDs (Globally Unique IDentifier)
		        The formal definition of the UUID string representation is provided by the following ABNF [7]:
		            UUID                   = time-low "-" time-mid "-"
		                                   time-high-and-version "-"
		                                   clock-seq-and-reserved
		                                   clock-seq-low "-" node
		            time-low               = 4hexOctet
		            time-mid               = 2hexOctet
		            time-high-and-version  = 2hexOctet
		            clock-seq-and-reserved = hexOctet
		            clock-seq-low          = hexOctet
		            node                   = 6hexOctet
		            hexOctet               = hexDigit hexDigit
		            hexDigit =
		                "0" / "1" / "2" / "3" / "4" / "5" / "6" / "7" / "8" / "9" /
		                "a" / "b" / "c" / "d" / "e" / "f" /
		                "A" / "B" / "C" / "D" / "E" / "F"
		    
		    https://github.com/victorquinn/chancejs/blob/develop/chance.js#L1349
		*/
		guid: function() {
			var pool = "abcdefABCDEF1234567890",
				guid = this.string(pool, 8) + '-' +
				this.string(pool, 4) + '-' +
				this.string(pool, 4) + '-' +
				this.string(pool, 4) + '-' +
				this.string(pool, 12);
			return guid
		},
		uuid: function() {
			return this.guid()
		},
		/*
		    éšæœºç”Ÿæˆä¸€ä¸ª 18 ä½èº«ä»½è¯ã€‚

		    [èº«ä»½è¯](http://baike.baidu.com/view/1697.htm#4)
		        åœ°å€ç  6 + å‡ºç”Ÿæ—¥æœŸç  8 + é¡ºåºç  3 + æ ¡éªŒç  1
		    [ã€Šä¸­åŽäººæ°‘å…±å’Œå›½è¡Œæ”¿åŒºåˆ’ä»£ç ã€‹å›½å®¶æ ‡å‡†(GB/T2260)](http://zhidao.baidu.com/question/1954561.html)
		*/
		id: function() {
			var id,
				sum = 0,
				rank = [
					"7", "9", "10", "5", "8", "4", "2", "1", "6", "3", "7", "9", "10", "5", "8", "4", "2"
				],
				last = [
					"1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2"
				]

			id = this.pick(DICT).id +
				this.date('yyyyMMdd') +
				this.string('number', 3)

			for (var i = 0; i < id.length; i++) {
				sum += id[i] * rank[i];
			}
			id += last[sum % 11];

			return id
		},

		/*
		    ç”Ÿæˆä¸€ä¸ªå…¨å±€çš„è‡ªå¢žæ•´æ•°ã€‚
		    ç±»ä¼¼è‡ªå¢žä¸»é”®ï¼ˆauto increment primary keyï¼‰ã€‚
		*/
		increment: function() {
			var key = 0
			return function(step) {
				return key += (+step || 1) // step?
			}
		}(),
		inc: function(step) {
			return this.increment(step)
		}
	}

/***/ },
/* 20 */
/***/ function(module, exports, __webpack_require__) {

	var Parser = __webpack_require__(21)
	var Handler = __webpack_require__(22)
	module.exports = {
		Parser: Parser,
		Handler: Handler
	}

/***/ },
/* 21 */
/***/ function(module, exports) {

	// https://github.com/nuysoft/regexp
	// forked from https://github.com/ForbesLindesay/regexp

	function parse(n) {
	    if ("string" != typeof n) {
	        var l = new TypeError("The regexp to parse must be represented as a string.");
	        throw l;
	    }
	    return index = 1, cgs = {}, parser.parse(n);
	}

	function Token(n) {
	    this.type = n, this.offset = Token.offset(), this.text = Token.text();
	}

	function Alternate(n, l) {
	    Token.call(this, "alternate"), this.left = n, this.right = l;
	}

	function Match(n) {
	    Token.call(this, "match"), this.body = n.filter(Boolean);
	}

	function Group(n, l) {
	    Token.call(this, n), this.body = l;
	}

	function CaptureGroup(n) {
	    Group.call(this, "capture-group"), this.index = cgs[this.offset] || (cgs[this.offset] = index++), 
	    this.body = n;
	}

	function Quantified(n, l) {
	    Token.call(this, "quantified"), this.body = n, this.quantifier = l;
	}

	function Quantifier(n, l) {
	    Token.call(this, "quantifier"), this.min = n, this.max = l, this.greedy = !0;
	}

	function CharSet(n, l) {
	    Token.call(this, "charset"), this.invert = n, this.body = l;
	}

	function CharacterRange(n, l) {
	    Token.call(this, "range"), this.start = n, this.end = l;
	}

	function Literal(n) {
	    Token.call(this, "literal"), this.body = n, this.escaped = this.body != this.text;
	}

	function Unicode(n) {
	    Token.call(this, "unicode"), this.code = n.toUpperCase();
	}

	function Hex(n) {
	    Token.call(this, "hex"), this.code = n.toUpperCase();
	}

	function Octal(n) {
	    Token.call(this, "octal"), this.code = n.toUpperCase();
	}

	function BackReference(n) {
	    Token.call(this, "back-reference"), this.code = n.toUpperCase();
	}

	function ControlCharacter(n) {
	    Token.call(this, "control-character"), this.code = n.toUpperCase();
	}

	var parser = function() {
	    function n(n, l) {
	        function u() {
	            this.constructor = n;
	        }
	        u.prototype = l.prototype, n.prototype = new u();
	    }
	    function l(n, l, u, t, r) {
	        function e(n, l) {
	            function u(n) {
	                function l(n) {
	                    return n.charCodeAt(0).toString(16).toUpperCase();
	                }
	                return n.replace(/\\/g, "\\\\").replace(/"/g, '\\"').replace(/\x08/g, "\\b").replace(/\t/g, "\\t").replace(/\n/g, "\\n").replace(/\f/g, "\\f").replace(/\r/g, "\\r").replace(/[\x00-\x07\x0B\x0E\x0F]/g, function(n) {
	                    return "\\x0" + l(n);
	                }).replace(/[\x10-\x1F\x80-\xFF]/g, function(n) {
	                    return "\\x" + l(n);
	                }).replace(/[\u0180-\u0FFF]/g, function(n) {
	                    return "\\u0" + l(n);
	                }).replace(/[\u1080-\uFFFF]/g, function(n) {
	                    return "\\u" + l(n);
	                });
	            }
	            var t, r;
	            switch (n.length) {
	              case 0:
	                t = "end of input";
	                break;

	              case 1:
	                t = n[0];
	                break;

	              default:
	                t = n.slice(0, -1).join(", ") + " or " + n[n.length - 1];
	            }
	            return r = l ? '"' + u(l) + '"' : "end of input", "Expected " + t + " but " + r + " found.";
	        }
	        this.expected = n, this.found = l, this.offset = u, this.line = t, this.column = r, 
	        this.name = "SyntaxError", this.message = e(n, l);
	    }
	    function u(n) {
	        function u() {
	            return n.substring(Lt, qt);
	        }
	        function t() {
	            return Lt;
	        }
	        function r(l) {
	            function u(l, u, t) {
	                var r, e;
	                for (r = u; t > r; r++) e = n.charAt(r), "\n" === e ? (l.seenCR || l.line++, l.column = 1, 
	                l.seenCR = !1) : "\r" === e || "\u2028" === e || "\u2029" === e ? (l.line++, l.column = 1, 
	                l.seenCR = !0) : (l.column++, l.seenCR = !1);
	            }
	            return Mt !== l && (Mt > l && (Mt = 0, Dt = {
	                line: 1,
	                column: 1,
	                seenCR: !1
	            }), u(Dt, Mt, l), Mt = l), Dt;
	        }
	        function e(n) {
	            Ht > qt || (qt > Ht && (Ht = qt, Ot = []), Ot.push(n));
	        }
	        function o(n) {
	            var l = 0;
	            for (n.sort(); l < n.length; ) n[l - 1] === n[l] ? n.splice(l, 1) : l++;
	        }
	        function c() {
	            var l, u, t, r, o;
	            return l = qt, u = i(), null !== u ? (t = qt, 124 === n.charCodeAt(qt) ? (r = fl, 
	            qt++) : (r = null, 0 === Wt && e(sl)), null !== r ? (o = c(), null !== o ? (r = [ r, o ], 
	            t = r) : (qt = t, t = il)) : (qt = t, t = il), null === t && (t = al), null !== t ? (Lt = l, 
	            u = hl(u, t), null === u ? (qt = l, l = u) : l = u) : (qt = l, l = il)) : (qt = l, 
	            l = il), l;
	        }
	        function i() {
	            var n, l, u, t, r;
	            if (n = qt, l = f(), null === l && (l = al), null !== l) if (u = qt, Wt++, t = d(), 
	            Wt--, null === t ? u = al : (qt = u, u = il), null !== u) {
	                for (t = [], r = h(), null === r && (r = a()); null !== r; ) t.push(r), r = h(), 
	                null === r && (r = a());
	                null !== t ? (r = s(), null === r && (r = al), null !== r ? (Lt = n, l = dl(l, t, r), 
	                null === l ? (qt = n, n = l) : n = l) : (qt = n, n = il)) : (qt = n, n = il);
	            } else qt = n, n = il; else qt = n, n = il;
	            return n;
	        }
	        function a() {
	            var n;
	            return n = x(), null === n && (n = Q(), null === n && (n = B())), n;
	        }
	        function f() {
	            var l, u;
	            return l = qt, 94 === n.charCodeAt(qt) ? (u = pl, qt++) : (u = null, 0 === Wt && e(vl)), 
	            null !== u && (Lt = l, u = wl()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function s() {
	            var l, u;
	            return l = qt, 36 === n.charCodeAt(qt) ? (u = Al, qt++) : (u = null, 0 === Wt && e(Cl)), 
	            null !== u && (Lt = l, u = gl()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function h() {
	            var n, l, u;
	            return n = qt, l = a(), null !== l ? (u = d(), null !== u ? (Lt = n, l = bl(l, u), 
	            null === l ? (qt = n, n = l) : n = l) : (qt = n, n = il)) : (qt = n, n = il), n;
	        }
	        function d() {
	            var n, l, u;
	            return Wt++, n = qt, l = p(), null !== l ? (u = k(), null === u && (u = al), null !== u ? (Lt = n, 
	            l = Tl(l, u), null === l ? (qt = n, n = l) : n = l) : (qt = n, n = il)) : (qt = n, 
	            n = il), Wt--, null === n && (l = null, 0 === Wt && e(kl)), n;
	        }
	        function p() {
	            var n;
	            return n = v(), null === n && (n = w(), null === n && (n = A(), null === n && (n = C(), 
	            null === n && (n = g(), null === n && (n = b()))))), n;
	        }
	        function v() {
	            var l, u, t, r, o, c;
	            return l = qt, 123 === n.charCodeAt(qt) ? (u = xl, qt++) : (u = null, 0 === Wt && e(yl)), 
	            null !== u ? (t = T(), null !== t ? (44 === n.charCodeAt(qt) ? (r = ml, qt++) : (r = null, 
	            0 === Wt && e(Rl)), null !== r ? (o = T(), null !== o ? (125 === n.charCodeAt(qt) ? (c = Fl, 
	            qt++) : (c = null, 0 === Wt && e(Ql)), null !== c ? (Lt = l, u = Sl(t, o), null === u ? (qt = l, 
	            l = u) : l = u) : (qt = l, l = il)) : (qt = l, l = il)) : (qt = l, l = il)) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function w() {
	            var l, u, t, r;
	            return l = qt, 123 === n.charCodeAt(qt) ? (u = xl, qt++) : (u = null, 0 === Wt && e(yl)), 
	            null !== u ? (t = T(), null !== t ? (n.substr(qt, 2) === Ul ? (r = Ul, qt += 2) : (r = null, 
	            0 === Wt && e(El)), null !== r ? (Lt = l, u = Gl(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il)) : (qt = l, l = il), l;
	        }
	        function A() {
	            var l, u, t, r;
	            return l = qt, 123 === n.charCodeAt(qt) ? (u = xl, qt++) : (u = null, 0 === Wt && e(yl)), 
	            null !== u ? (t = T(), null !== t ? (125 === n.charCodeAt(qt) ? (r = Fl, qt++) : (r = null, 
	            0 === Wt && e(Ql)), null !== r ? (Lt = l, u = Bl(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il)) : (qt = l, l = il), l;
	        }
	        function C() {
	            var l, u;
	            return l = qt, 43 === n.charCodeAt(qt) ? (u = jl, qt++) : (u = null, 0 === Wt && e($l)), 
	            null !== u && (Lt = l, u = ql()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function g() {
	            var l, u;
	            return l = qt, 42 === n.charCodeAt(qt) ? (u = Ll, qt++) : (u = null, 0 === Wt && e(Ml)), 
	            null !== u && (Lt = l, u = Dl()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function b() {
	            var l, u;
	            return l = qt, 63 === n.charCodeAt(qt) ? (u = Hl, qt++) : (u = null, 0 === Wt && e(Ol)), 
	            null !== u && (Lt = l, u = Wl()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function k() {
	            var l;
	            return 63 === n.charCodeAt(qt) ? (l = Hl, qt++) : (l = null, 0 === Wt && e(Ol)), 
	            l;
	        }
	        function T() {
	            var l, u, t;
	            if (l = qt, u = [], zl.test(n.charAt(qt)) ? (t = n.charAt(qt), qt++) : (t = null, 
	            0 === Wt && e(Il)), null !== t) for (;null !== t; ) u.push(t), zl.test(n.charAt(qt)) ? (t = n.charAt(qt), 
	            qt++) : (t = null, 0 === Wt && e(Il)); else u = il;
	            return null !== u && (Lt = l, u = Jl(u)), null === u ? (qt = l, l = u) : l = u, 
	            l;
	        }
	        function x() {
	            var l, u, t, r;
	            return l = qt, 40 === n.charCodeAt(qt) ? (u = Kl, qt++) : (u = null, 0 === Wt && e(Nl)), 
	            null !== u ? (t = R(), null === t && (t = F(), null === t && (t = m(), null === t && (t = y()))), 
	            null !== t ? (41 === n.charCodeAt(qt) ? (r = Pl, qt++) : (r = null, 0 === Wt && e(Vl)), 
	            null !== r ? (Lt = l, u = Xl(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il)) : (qt = l, l = il), l;
	        }
	        function y() {
	            var n, l;
	            return n = qt, l = c(), null !== l && (Lt = n, l = Yl(l)), null === l ? (qt = n, 
	            n = l) : n = l, n;
	        }
	        function m() {
	            var l, u, t;
	            return l = qt, n.substr(qt, 2) === Zl ? (u = Zl, qt += 2) : (u = null, 0 === Wt && e(_l)), 
	            null !== u ? (t = c(), null !== t ? (Lt = l, u = nu(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function R() {
	            var l, u, t;
	            return l = qt, n.substr(qt, 2) === lu ? (u = lu, qt += 2) : (u = null, 0 === Wt && e(uu)), 
	            null !== u ? (t = c(), null !== t ? (Lt = l, u = tu(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function F() {
	            var l, u, t;
	            return l = qt, n.substr(qt, 2) === ru ? (u = ru, qt += 2) : (u = null, 0 === Wt && e(eu)), 
	            null !== u ? (t = c(), null !== t ? (Lt = l, u = ou(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function Q() {
	            var l, u, t, r, o;
	            if (Wt++, l = qt, 91 === n.charCodeAt(qt) ? (u = iu, qt++) : (u = null, 0 === Wt && e(au)), 
	            null !== u) if (94 === n.charCodeAt(qt) ? (t = pl, qt++) : (t = null, 0 === Wt && e(vl)), 
	            null === t && (t = al), null !== t) {
	                for (r = [], o = S(), null === o && (o = U()); null !== o; ) r.push(o), o = S(), 
	                null === o && (o = U());
	                null !== r ? (93 === n.charCodeAt(qt) ? (o = fu, qt++) : (o = null, 0 === Wt && e(su)), 
	                null !== o ? (Lt = l, u = hu(t, r), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	                l = il)) : (qt = l, l = il);
	            } else qt = l, l = il; else qt = l, l = il;
	            return Wt--, null === l && (u = null, 0 === Wt && e(cu)), l;
	        }
	        function S() {
	            var l, u, t, r;
	            return Wt++, l = qt, u = U(), null !== u ? (45 === n.charCodeAt(qt) ? (t = pu, qt++) : (t = null, 
	            0 === Wt && e(vu)), null !== t ? (r = U(), null !== r ? (Lt = l, u = wu(u, r), null === u ? (qt = l, 
	            l = u) : l = u) : (qt = l, l = il)) : (qt = l, l = il)) : (qt = l, l = il), Wt--, 
	            null === l && (u = null, 0 === Wt && e(du)), l;
	        }
	        function U() {
	            var n, l;
	            return Wt++, n = G(), null === n && (n = E()), Wt--, null === n && (l = null, 0 === Wt && e(Au)), 
	            n;
	        }
	        function E() {
	            var l, u;
	            return l = qt, Cu.test(n.charAt(qt)) ? (u = n.charAt(qt), qt++) : (u = null, 0 === Wt && e(gu)), 
	            null !== u && (Lt = l, u = bu(u)), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function G() {
	            var n;
	            return n = L(), null === n && (n = Y(), null === n && (n = H(), null === n && (n = O(), 
	            null === n && (n = W(), null === n && (n = z(), null === n && (n = I(), null === n && (n = J(), 
	            null === n && (n = K(), null === n && (n = N(), null === n && (n = P(), null === n && (n = V(), 
	            null === n && (n = X(), null === n && (n = _(), null === n && (n = nl(), null === n && (n = ll(), 
	            null === n && (n = ul(), null === n && (n = tl()))))))))))))))))), n;
	        }
	        function B() {
	            var n;
	            return n = j(), null === n && (n = q(), null === n && (n = $())), n;
	        }
	        function j() {
	            var l, u;
	            return l = qt, 46 === n.charCodeAt(qt) ? (u = ku, qt++) : (u = null, 0 === Wt && e(Tu)), 
	            null !== u && (Lt = l, u = xu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function $() {
	            var l, u;
	            return Wt++, l = qt, mu.test(n.charAt(qt)) ? (u = n.charAt(qt), qt++) : (u = null, 
	            0 === Wt && e(Ru)), null !== u && (Lt = l, u = bu(u)), null === u ? (qt = l, l = u) : l = u, 
	            Wt--, null === l && (u = null, 0 === Wt && e(yu)), l;
	        }
	        function q() {
	            var n;
	            return n = M(), null === n && (n = D(), null === n && (n = Y(), null === n && (n = H(), 
	            null === n && (n = O(), null === n && (n = W(), null === n && (n = z(), null === n && (n = I(), 
	            null === n && (n = J(), null === n && (n = K(), null === n && (n = N(), null === n && (n = P(), 
	            null === n && (n = V(), null === n && (n = X(), null === n && (n = Z(), null === n && (n = _(), 
	            null === n && (n = nl(), null === n && (n = ll(), null === n && (n = ul(), null === n && (n = tl()))))))))))))))))))), 
	            n;
	        }
	        function L() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Fu ? (u = Fu, qt += 2) : (u = null, 0 === Wt && e(Qu)), 
	            null !== u && (Lt = l, u = Su()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function M() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Fu ? (u = Fu, qt += 2) : (u = null, 0 === Wt && e(Qu)), 
	            null !== u && (Lt = l, u = Uu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function D() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Eu ? (u = Eu, qt += 2) : (u = null, 0 === Wt && e(Gu)), 
	            null !== u && (Lt = l, u = Bu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function H() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === ju ? (u = ju, qt += 2) : (u = null, 0 === Wt && e($u)), 
	            null !== u && (Lt = l, u = qu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function O() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Lu ? (u = Lu, qt += 2) : (u = null, 0 === Wt && e(Mu)), 
	            null !== u && (Lt = l, u = Du()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function W() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Hu ? (u = Hu, qt += 2) : (u = null, 0 === Wt && e(Ou)), 
	            null !== u && (Lt = l, u = Wu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function z() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === zu ? (u = zu, qt += 2) : (u = null, 0 === Wt && e(Iu)), 
	            null !== u && (Lt = l, u = Ju()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function I() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Ku ? (u = Ku, qt += 2) : (u = null, 0 === Wt && e(Nu)), 
	            null !== u && (Lt = l, u = Pu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function J() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Vu ? (u = Vu, qt += 2) : (u = null, 0 === Wt && e(Xu)), 
	            null !== u && (Lt = l, u = Yu()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function K() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Zu ? (u = Zu, qt += 2) : (u = null, 0 === Wt && e(_u)), 
	            null !== u && (Lt = l, u = nt()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function N() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === lt ? (u = lt, qt += 2) : (u = null, 0 === Wt && e(ut)), 
	            null !== u && (Lt = l, u = tt()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function P() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === rt ? (u = rt, qt += 2) : (u = null, 0 === Wt && e(et)), 
	            null !== u && (Lt = l, u = ot()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function V() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === ct ? (u = ct, qt += 2) : (u = null, 0 === Wt && e(it)), 
	            null !== u && (Lt = l, u = at()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function X() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === ft ? (u = ft, qt += 2) : (u = null, 0 === Wt && e(st)), 
	            null !== u && (Lt = l, u = ht()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function Y() {
	            var l, u, t;
	            return l = qt, n.substr(qt, 2) === dt ? (u = dt, qt += 2) : (u = null, 0 === Wt && e(pt)), 
	            null !== u ? (n.length > qt ? (t = n.charAt(qt), qt++) : (t = null, 0 === Wt && e(vt)), 
	            null !== t ? (Lt = l, u = wt(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function Z() {
	            var l, u, t;
	            return l = qt, 92 === n.charCodeAt(qt) ? (u = At, qt++) : (u = null, 0 === Wt && e(Ct)), 
	            null !== u ? (gt.test(n.charAt(qt)) ? (t = n.charAt(qt), qt++) : (t = null, 0 === Wt && e(bt)), 
	            null !== t ? (Lt = l, u = kt(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        function _() {
	            var l, u, t, r;
	            if (l = qt, n.substr(qt, 2) === Tt ? (u = Tt, qt += 2) : (u = null, 0 === Wt && e(xt)), 
	            null !== u) {
	                if (t = [], yt.test(n.charAt(qt)) ? (r = n.charAt(qt), qt++) : (r = null, 0 === Wt && e(mt)), 
	                null !== r) for (;null !== r; ) t.push(r), yt.test(n.charAt(qt)) ? (r = n.charAt(qt), 
	                qt++) : (r = null, 0 === Wt && e(mt)); else t = il;
	                null !== t ? (Lt = l, u = Rt(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	                l = il);
	            } else qt = l, l = il;
	            return l;
	        }
	        function nl() {
	            var l, u, t, r;
	            if (l = qt, n.substr(qt, 2) === Ft ? (u = Ft, qt += 2) : (u = null, 0 === Wt && e(Qt)), 
	            null !== u) {
	                if (t = [], St.test(n.charAt(qt)) ? (r = n.charAt(qt), qt++) : (r = null, 0 === Wt && e(Ut)), 
	                null !== r) for (;null !== r; ) t.push(r), St.test(n.charAt(qt)) ? (r = n.charAt(qt), 
	                qt++) : (r = null, 0 === Wt && e(Ut)); else t = il;
	                null !== t ? (Lt = l, u = Et(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	                l = il);
	            } else qt = l, l = il;
	            return l;
	        }
	        function ll() {
	            var l, u, t, r;
	            if (l = qt, n.substr(qt, 2) === Gt ? (u = Gt, qt += 2) : (u = null, 0 === Wt && e(Bt)), 
	            null !== u) {
	                if (t = [], St.test(n.charAt(qt)) ? (r = n.charAt(qt), qt++) : (r = null, 0 === Wt && e(Ut)), 
	                null !== r) for (;null !== r; ) t.push(r), St.test(n.charAt(qt)) ? (r = n.charAt(qt), 
	                qt++) : (r = null, 0 === Wt && e(Ut)); else t = il;
	                null !== t ? (Lt = l, u = jt(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	                l = il);
	            } else qt = l, l = il;
	            return l;
	        }
	        function ul() {
	            var l, u;
	            return l = qt, n.substr(qt, 2) === Tt ? (u = Tt, qt += 2) : (u = null, 0 === Wt && e(xt)), 
	            null !== u && (Lt = l, u = $t()), null === u ? (qt = l, l = u) : l = u, l;
	        }
	        function tl() {
	            var l, u, t;
	            return l = qt, 92 === n.charCodeAt(qt) ? (u = At, qt++) : (u = null, 0 === Wt && e(Ct)), 
	            null !== u ? (n.length > qt ? (t = n.charAt(qt), qt++) : (t = null, 0 === Wt && e(vt)), 
	            null !== t ? (Lt = l, u = bu(t), null === u ? (qt = l, l = u) : l = u) : (qt = l, 
	            l = il)) : (qt = l, l = il), l;
	        }
	        var rl, el = arguments.length > 1 ? arguments[1] : {}, ol = {
	            regexp: c
	        }, cl = c, il = null, al = "", fl = "|", sl = '"|"', hl = function(n, l) {
	            return l ? new Alternate(n, l[1]) : n;
	        }, dl = function(n, l, u) {
	            return new Match([ n ].concat(l).concat([ u ]));
	        }, pl = "^", vl = '"^"', wl = function() {
	            return new Token("start");
	        }, Al = "$", Cl = '"$"', gl = function() {
	            return new Token("end");
	        }, bl = function(n, l) {
	            return new Quantified(n, l);
	        }, kl = "Quantifier", Tl = function(n, l) {
	            return l && (n.greedy = !1), n;
	        }, xl = "{", yl = '"{"', ml = ",", Rl = '","', Fl = "}", Ql = '"}"', Sl = function(n, l) {
	            return new Quantifier(n, l);
	        }, Ul = ",}", El = '",}"', Gl = function(n) {
	            return new Quantifier(n, 1/0);
	        }, Bl = function(n) {
	            return new Quantifier(n, n);
	        }, jl = "+", $l = '"+"', ql = function() {
	            return new Quantifier(1, 1/0);
	        }, Ll = "*", Ml = '"*"', Dl = function() {
	            return new Quantifier(0, 1/0);
	        }, Hl = "?", Ol = '"?"', Wl = function() {
	            return new Quantifier(0, 1);
	        }, zl = /^[0-9]/, Il = "[0-9]", Jl = function(n) {
	            return +n.join("");
	        }, Kl = "(", Nl = '"("', Pl = ")", Vl = '")"', Xl = function(n) {
	            return n;
	        }, Yl = function(n) {
	            return new CaptureGroup(n);
	        }, Zl = "?:", _l = '"?:"', nu = function(n) {
	            return new Group("non-capture-group", n);
	        }, lu = "?=", uu = '"?="', tu = function(n) {
	            return new Group("positive-lookahead", n);
	        }, ru = "?!", eu = '"?!"', ou = function(n) {
	            return new Group("negative-lookahead", n);
	        }, cu = "CharacterSet", iu = "[", au = '"["', fu = "]", su = '"]"', hu = function(n, l) {
	            return new CharSet(!!n, l);
	        }, du = "CharacterRange", pu = "-", vu = '"-"', wu = function(n, l) {
	            return new CharacterRange(n, l);
	        }, Au = "Character", Cu = /^[^\\\]]/, gu = "[^\\\\\\]]", bu = function(n) {
	            return new Literal(n);
	        }, ku = ".", Tu = '"."', xu = function() {
	            return new Token("any-character");
	        }, yu = "Literal", mu = /^[^|\\\/.[()?+*$\^]/, Ru = "[^|\\\\\\/.[()?+*$\\^]", Fu = "\\b", Qu = '"\\\\b"', Su = function() {
	            return new Token("backspace");
	        }, Uu = function() {
	            return new Token("word-boundary");
	        }, Eu = "\\B", Gu = '"\\\\B"', Bu = function() {
	            return new Token("non-word-boundary");
	        }, ju = "\\d", $u = '"\\\\d"', qu = function() {
	            return new Token("digit");
	        }, Lu = "\\D", Mu = '"\\\\D"', Du = function() {
	            return new Token("non-digit");
	        }, Hu = "\\f", Ou = '"\\\\f"', Wu = function() {
	            return new Token("form-feed");
	        }, zu = "\\n", Iu = '"\\\\n"', Ju = function() {
	            return new Token("line-feed");
	        }, Ku = "\\r", Nu = '"\\\\r"', Pu = function() {
	            return new Token("carriage-return");
	        }, Vu = "\\s", Xu = '"\\\\s"', Yu = function() {
	            return new Token("white-space");
	        }, Zu = "\\S", _u = '"\\\\S"', nt = function() {
	            return new Token("non-white-space");
	        }, lt = "\\t", ut = '"\\\\t"', tt = function() {
	            return new Token("tab");
	        }, rt = "\\v", et = '"\\\\v"', ot = function() {
	            return new Token("vertical-tab");
	        }, ct = "\\w", it = '"\\\\w"', at = function() {
	            return new Token("word");
	        }, ft = "\\W", st = '"\\\\W"', ht = function() {
	            return new Token("non-word");
	        }, dt = "\\c", pt = '"\\\\c"', vt = "any character", wt = function(n) {
	            return new ControlCharacter(n);
	        }, At = "\\", Ct = '"\\\\"', gt = /^[1-9]/, bt = "[1-9]", kt = function(n) {
	            return new BackReference(n);
	        }, Tt = "\\0", xt = '"\\\\0"', yt = /^[0-7]/, mt = "[0-7]", Rt = function(n) {
	            return new Octal(n.join(""));
	        }, Ft = "\\x", Qt = '"\\\\x"', St = /^[0-9a-fA-F]/, Ut = "[0-9a-fA-F]", Et = function(n) {
	            return new Hex(n.join(""));
	        }, Gt = "\\u", Bt = '"\\\\u"', jt = function(n) {
	            return new Unicode(n.join(""));
	        }, $t = function() {
	            return new Token("null-character");
	        }, qt = 0, Lt = 0, Mt = 0, Dt = {
	            line: 1,
	            column: 1,
	            seenCR: !1
	        }, Ht = 0, Ot = [], Wt = 0;
	        if ("startRule" in el) {
	            if (!(el.startRule in ol)) throw new Error("Can't start parsing from rule \"" + el.startRule + '".');
	            cl = ol[el.startRule];
	        }
	        if (Token.offset = t, Token.text = u, rl = cl(), null !== rl && qt === n.length) return rl;
	        throw o(Ot), Lt = Math.max(qt, Ht), new l(Ot, Lt < n.length ? n.charAt(Lt) : null, Lt, r(Lt).line, r(Lt).column);
	    }
	    return n(l, Error), {
	        SyntaxError: l,
	        parse: u
	    };
	}(), index = 1, cgs = {};

	module.exports = parser

/***/ },
/* 22 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## RegExp Handler

	    https://github.com/ForbesLindesay/regexp
	    https://github.com/dmajda/pegjs
	    http://www.regexper.com/

	    æ¯ä¸ªèŠ‚ç‚¹çš„ç»“æž„
	        {
	            type: '',
	            offset: number,
	            text: '',
	            body: {},
	            escaped: true/false
	        }

	    type å¯é€‰å€¼
	        alternate             |         é€‰æ‹©
	        match                 åŒ¹é…
	        capture-group         ()        æ•èŽ·ç»„
	        non-capture-group     (?:...)   éžæ•èŽ·ç»„
	        positive-lookahead    (?=p)     é›¶å®½æ­£å‘å…ˆè¡Œæ–­è¨€
	        negative-lookahead    (?!p)     é›¶å®½è´Ÿå‘å…ˆè¡Œæ–­è¨€
	        quantified            a*        é‡å¤èŠ‚ç‚¹
	        quantifier            *         é‡è¯
	        charset               []        å­—ç¬¦é›†
	        range                 {m, n}    èŒƒå›´
	        literal               a         ç›´æŽ¥é‡å­—ç¬¦
	        unicode               \uxxxx    Unicode
	        hex                   \x        åå…­è¿›åˆ¶
	        octal                 å…«è¿›åˆ¶
	        back-reference        \n        åå‘å¼•ç”¨
	        control-character     \cX       æŽ§åˆ¶å­—ç¬¦

	        // Token
	        start               ^       å¼€å¤´
	        end                 $       ç»“å°¾
	        any-character       .       ä»»æ„å­—ç¬¦
	        backspace           [\b]    é€€æ ¼ç›´æŽ¥é‡
	        word-boundary       \b      å•è¯è¾¹ç•Œ
	        non-word-boundary   \B      éžå•è¯è¾¹ç•Œ
	        digit               \d      ASCII æ•°å­—ï¼Œ[0-9]
	        non-digit           \D      éž ASCII æ•°å­—ï¼Œ[^0-9]
	        form-feed           \f      æ¢é¡µç¬¦
	        line-feed           \n      æ¢è¡Œç¬¦
	        carriage-return     \r      å›žè½¦ç¬¦
	        white-space         \s      ç©ºç™½ç¬¦
	        non-white-space     \S      éžç©ºç™½ç¬¦
	        tab                 \t      åˆ¶è¡¨ç¬¦
	        vertical-tab        \v      åž‚ç›´åˆ¶è¡¨ç¬¦
	        word                \w      ASCII å­—ç¬¦ï¼Œ[a-zA-Z0-9]
	        non-word            \W      éž ASCII å­—ç¬¦ï¼Œ[^a-zA-Z0-9]
	        null-character      \o      NUL å­—ç¬¦
	 */

	var Util = __webpack_require__(3)
	var Random = __webpack_require__(5)
	    /*
	        
	    */
	var Handler = {
	    extend: Util.extend
	}

	// http://en.wikipedia.org/wiki/ASCII#ASCII_printable_code_chart
	/*var ASCII_CONTROL_CODE_CHART = {
	    '@': ['\u0000'],
	    A: ['\u0001'],
	    B: ['\u0002'],
	    C: ['\u0003'],
	    D: ['\u0004'],
	    E: ['\u0005'],
	    F: ['\u0006'],
	    G: ['\u0007', '\a'],
	    H: ['\u0008', '\b'],
	    I: ['\u0009', '\t'],
	    J: ['\u000A', '\n'],
	    K: ['\u000B', '\v'],
	    L: ['\u000C', '\f'],
	    M: ['\u000D', '\r'],
	    N: ['\u000E'],
	    O: ['\u000F'],
	    P: ['\u0010'],
	    Q: ['\u0011'],
	    R: ['\u0012'],
	    S: ['\u0013'],
	    T: ['\u0014'],
	    U: ['\u0015'],
	    V: ['\u0016'],
	    W: ['\u0017'],
	    X: ['\u0018'],
	    Y: ['\u0019'],
	    Z: ['\u001A'],
	    '[': ['\u001B', '\e'],
	    '\\': ['\u001C'],
	    ']': ['\u001D'],
	    '^': ['\u001E'],
	    '_': ['\u001F']
	}*/

	// ASCII printable code chart
	// var LOWER = 'abcdefghijklmnopqrstuvwxyz'
	// var UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
	// var NUMBER = '0123456789'
	// var SYMBOL = ' !"#$%&\'()*+,-./' + ':;<=>?@' + '[\\]^_`' + '{|}~'
	var LOWER = ascii(97, 122)
	var UPPER = ascii(65, 90)
	var NUMBER = ascii(48, 57)
	var OTHER = ascii(32, 47) + ascii(58, 64) + ascii(91, 96) + ascii(123, 126) // æŽ’é™¤ 95 _ ascii(91, 94) + ascii(96, 96)
	var PRINTABLE = ascii(32, 126)
	var SPACE = ' \f\n\r\t\v\u00A0\u2028\u2029'
	var CHARACTER_CLASSES = {
	    '\\w': LOWER + UPPER + NUMBER + '_', // ascii(95, 95)
	    '\\W': OTHER.replace('_', ''),
	    '\\s': SPACE,
	    '\\S': function() {
	        var result = PRINTABLE
	        for (var i = 0; i < SPACE.length; i++) {
	            result = result.replace(SPACE[i], '')
	        }
	        return result
	    }(),
	    '\\d': NUMBER,
	    '\\D': LOWER + UPPER + OTHER
	}

	function ascii(from, to) {
	    var result = ''
	    for (var i = from; i <= to; i++) {
	        result += String.fromCharCode(i)
	    }
	    return result
	}

	// var ast = RegExpParser.parse(regexp.source)
	Handler.gen = function(node, result, cache) {
	    cache = cache || {
	        guid: 1
	    }
	    return Handler[node.type] ? Handler[node.type](node, result, cache) :
	        Handler.token(node, result, cache)
	}

	Handler.extend({
	    /* jshint unused:false */
	    token: function(node, result, cache) {
	        switch (node.type) {
	            case 'start':
	            case 'end':
	                return ''
	            case 'any-character':
	                return Random.character()
	            case 'backspace':
	                return ''
	            case 'word-boundary': // TODO
	                return ''
	            case 'non-word-boundary': // TODO
	                break
	            case 'digit':
	                return Random.pick(
	                    NUMBER.split('')
	                )
	            case 'non-digit':
	                return Random.pick(
	                    (LOWER + UPPER + OTHER).split('')
	                )
	            case 'form-feed':
	                break
	            case 'line-feed':
	                return node.body || node.text
	            case 'carriage-return':
	                break
	            case 'white-space':
	                return Random.pick(
	                    SPACE.split('')
	                )
	            case 'non-white-space':
	                return Random.pick(
	                    (LOWER + UPPER + NUMBER).split('')
	                )
	            case 'tab':
	                break
	            case 'vertical-tab':
	                break
	            case 'word': // \w [a-zA-Z0-9]
	                return Random.pick(
	                    (LOWER + UPPER + NUMBER).split('')
	                )
	            case 'non-word': // \W [^a-zA-Z0-9]
	                return Random.pick(
	                    OTHER.replace('_', '').split('')
	                )
	            case 'null-character':
	                break
	        }
	        return node.body || node.text
	    },
	    /*
	        {
	            type: 'alternate',
	            offset: 0,
	            text: '',
	            left: {
	                boyd: []
	            },
	            right: {
	                boyd: []
	            }
	        }
	    */
	    alternate: function(node, result, cache) {
	        // node.left/right {}
	        return this.gen(
	            Random.boolean() ? node.left : node.right,
	            result,
	            cache
	        )
	    },
	    /*
	        {
	            type: 'match',
	            offset: 0,
	            text: '',
	            body: []
	        }
	    */
	    match: function(node, result, cache) {
	        result = ''
	            // node.body []
	        for (var i = 0; i < node.body.length; i++) {
	            result += this.gen(node.body[i], result, cache)
	        }
	        return result
	    },
	    // ()
	    'capture-group': function(node, result, cache) {
	        // node.body {}
	        result = this.gen(node.body, result, cache)
	        cache[cache.guid++] = result
	        return result
	    },
	    // (?:...)
	    'non-capture-group': function(node, result, cache) {
	        // node.body {}
	        return this.gen(node.body, result, cache)
	    },
	    // (?=p)
	    'positive-lookahead': function(node, result, cache) {
	        // node.body
	        return this.gen(node.body, result, cache)
	    },
	    // (?!p)
	    'negative-lookahead': function(node, result, cache) {
	        // node.body
	        return ''
	    },
	    /*
	        {
	            type: 'quantified',
	            offset: 3,
	            text: 'c*',
	            body: {
	                type: 'literal',
	                offset: 3,
	                text: 'c',
	                body: 'c',
	                escaped: false
	            },
	            quantifier: {
	                type: 'quantifier',
	                offset: 4,
	                text: '*',
	                min: 0,
	                max: Infinity,
	                greedy: true
	            }
	        }
	    */
	    quantified: function(node, result, cache) {
	        result = ''
	            // node.quantifier {}
	        var count = this.quantifier(node.quantifier);
	        // node.body {}
	        for (var i = 0; i < count; i++) {
	            result += this.gen(node.body, result, cache)
	        }
	        return result
	    },
	    /*
	        quantifier: {
	            type: 'quantifier',
	            offset: 4,
	            text: '*',
	            min: 0,
	            max: Infinity,
	            greedy: true
	        }
	    */
	    quantifier: function(node, result, cache) {
	        var min = Math.max(node.min, 0)
	        var max = isFinite(node.max) ? node.max :
	            min + Random.integer(3, 7)
	        return Random.integer(min, max)
	    },
	    /*
	        
	    */
	    charset: function(node, result, cache) {
	        // node.invert
	        if (node.invert) return this['invert-charset'](node, result, cache)

	        // node.body []
	        var literal = Random.pick(node.body)
	        return this.gen(literal, result, cache)
	    },
	    'invert-charset': function(node, result, cache) {
	        var pool = PRINTABLE
	        for (var i = 0, item; i < node.body.length; i++) {
	            item = node.body[i]
	            switch (item.type) {
	                case 'literal':
	                    pool = pool.replace(item.body, '')
	                    break
	                case 'range':
	                    var min = this.gen(item.start, result, cache).charCodeAt()
	                    var max = this.gen(item.end, result, cache).charCodeAt()
	                    for (var ii = min; ii <= max; ii++) {
	                        pool = pool.replace(String.fromCharCode(ii), '')
	                    }
	                    /* falls through */
	                default:
	                    var characters = CHARACTER_CLASSES[item.text]
	                    if (characters) {
	                        for (var iii = 0; iii <= characters.length; iii++) {
	                            pool = pool.replace(characters[iii], '')
	                        }
	                    }
	            }
	        }
	        return Random.pick(pool.split(''))
	    },
	    range: function(node, result, cache) {
	        // node.start, node.end
	        var min = this.gen(node.start, result, cache).charCodeAt()
	        var max = this.gen(node.end, result, cache).charCodeAt()
	        return String.fromCharCode(
	            Random.integer(min, max)
	        )
	    },
	    literal: function(node, result, cache) {
	        return node.escaped ? node.body : node.text
	    },
	    // Unicode \u
	    unicode: function(node, result, cache) {
	        return String.fromCharCode(
	            parseInt(node.code, 16)
	        )
	    },
	    // åå…­è¿›åˆ¶ \xFF
	    hex: function(node, result, cache) {
	        return String.fromCharCode(
	            parseInt(node.code, 16)
	        )
	    },
	    // å…«è¿›åˆ¶ \0
	    octal: function(node, result, cache) {
	        return String.fromCharCode(
	            parseInt(node.code, 8)
	        )
	    },
	    // åå‘å¼•ç”¨
	    'back-reference': function(node, result, cache) {
	        return cache[node.code] || ''
	    },
	    /*
	        http://en.wikipedia.org/wiki/C0_and_C1_control_codes
	    */
	    CONTROL_CHARACTER_MAP: function() {
	        var CONTROL_CHARACTER = '@ A B C D E F G H I J K L M N O P Q R S T U V W X Y Z [ \\ ] ^ _'.split(' ')
	        var CONTROL_CHARACTER_UNICODE = '\u0000 \u0001 \u0002 \u0003 \u0004 \u0005 \u0006 \u0007 \u0008 \u0009 \u000A \u000B \u000C \u000D \u000E \u000F \u0010 \u0011 \u0012 \u0013 \u0014 \u0015 \u0016 \u0017 \u0018 \u0019 \u001A \u001B \u001C \u001D \u001E \u001F'.split(' ')
	        var map = {}
	        for (var i = 0; i < CONTROL_CHARACTER.length; i++) {
	            map[CONTROL_CHARACTER[i]] = CONTROL_CHARACTER_UNICODE[i]
	        }
	        return map
	    }(),
	    'control-character': function(node, result, cache) {
	        return this.CONTROL_CHARACTER_MAP[node.code]
	    }
	})

	module.exports = Handler

/***/ },
/* 23 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(24)

/***/ },
/* 24 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## toJSONSchema

	    æŠŠ Mock.js é£Žæ ¼çš„æ•°æ®æ¨¡æ¿è½¬æ¢æˆ JSON Schemaã€‚

	    > [JSON Schema](http://json-schema.org/)
	 */
	var Constant = __webpack_require__(2)
	var Util = __webpack_require__(3)
	var Parser = __webpack_require__(4)

	function toJSONSchema(template, name, path /* Internal Use Only */ ) {
	    // type rule properties items
	    path = path || []
	    var result = {
	        name: typeof name === 'string' ? name.replace(Constant.RE_KEY, '$1') : name,
	        template: template,
	        type: Util.type(template), // å¯èƒ½ä¸å‡†ç¡®ï¼Œä¾‹å¦‚ { 'name|1': [{}, {} ...] }
	        rule: Parser.parse(name)
	    }
	    result.path = path.slice(0)
	    result.path.push(name === undefined ? 'ROOT' : result.name)

	    switch (result.type) {
	        case 'array':
	            result.items = []
	            Util.each(template, function(value, index) {
	                result.items.push(
	                    toJSONSchema(value, index, result.path)
	                )
	            })
	            break
	        case 'object':
	            result.properties = []
	            Util.each(template, function(value, name) {
	                result.properties.push(
	                    toJSONSchema(value, name, result.path)
	                )
	            })
	            break
	    }

	    return result

	}

	module.exports = toJSONSchema


/***/ },
/* 25 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(26)

/***/ },
/* 26 */
/***/ function(module, exports, __webpack_require__) {

	/*
	    ## valid(template, data)

	    æ ¡éªŒçœŸå®žæ•°æ® data æ˜¯å¦ä¸Žæ•°æ®æ¨¡æ¿ template åŒ¹é…ã€‚
	    
	    å®žçŽ°æ€è·¯ï¼š
	    1. è§£æžè§„åˆ™ã€‚
	        å…ˆæŠŠæ•°æ®æ¨¡æ¿ template è§£æžä¸ºæ›´æ–¹ä¾¿æœºå™¨è§£æžçš„ JSON-Schame
	        name               å±žæ€§å 
	        type               å±žæ€§å€¼ç±»åž‹
	        template           å±žæ€§å€¼æ¨¡æ¿
	        properties         å¯¹è±¡å±žæ€§æ•°ç»„
	        items              æ•°ç»„å…ƒç´ æ•°ç»„
	        rule               å±žæ€§å€¼ç”Ÿæˆè§„åˆ™
	    2. é€’å½’éªŒè¯è§„åˆ™ã€‚
	        ç„¶åŽç”¨ JSON-Schema æ ¡éªŒçœŸå®žæ•°æ®ï¼Œæ ¡éªŒé¡¹åŒ…æ‹¬å±žæ€§åã€å€¼ç±»åž‹ã€å€¼ã€å€¼ç”Ÿæˆè§„åˆ™ã€‚

	    æç¤ºä¿¡æ¯ 
	    https://github.com/fge/json-schema-validator/blob/master/src/main/resources/com/github/fge/jsonschema/validator/validation.properties
	    [JSON-Schama validator](http://json-schema-validator.herokuapp.com/)
	    [Regexp Demo](http://demos.forbeslindesay.co.uk/regexp/)
	*/
	var Constant = __webpack_require__(2)
	var Util = __webpack_require__(3)
	var toJSONSchema = __webpack_require__(23)

	function valid(template, data) {
	    var schema = toJSONSchema(template)
	    var result = Diff.diff(schema, data)
	    for (var i = 0; i < result.length; i++) {
	        // console.log(Assert.message(result[i]))
	    }
	    return result
	}

	/*
	    ## name
	        æœ‰ç”Ÿæˆè§„åˆ™ï¼šæ¯”è¾ƒè§£æžåŽçš„ name
	        æ— ç”Ÿæˆè§„åˆ™ï¼šç›´æŽ¥æ¯”è¾ƒ
	    ## type
	        æ— ç±»åž‹è½¬æ¢ï¼šç›´æŽ¥æ¯”è¾ƒ
	        æœ‰ç±»åž‹è½¬æ¢ï¼šå…ˆè¯•ç€è§£æž templateï¼Œç„¶åŽå†æ£€æŸ¥ï¼Ÿ
	    ## value vs. template
	        åŸºæœ¬ç±»åž‹
	            æ— ç”Ÿæˆè§„åˆ™ï¼šç›´æŽ¥æ¯”è¾ƒ
	            æœ‰ç”Ÿæˆè§„åˆ™ï¼š
	                number
	                    min-max.dmin-dmax
	                    min-max.dcount
	                    count.dmin-dmax
	                    count.dcount
	                    +step
	                    æ•´æ•°éƒ¨åˆ†
	                    å°æ•°éƒ¨åˆ†
	                boolean 
	                string  
	                    min-max
	                    count
	    ## properties
	        å¯¹è±¡
	            æœ‰ç”Ÿæˆè§„åˆ™ï¼šæ£€æµ‹æœŸæœ›çš„å±žæ€§ä¸ªæ•°ï¼Œç»§ç»­é€’å½’
	            æ— ç”Ÿæˆè§„åˆ™ï¼šæ£€æµ‹å…¨éƒ¨çš„å±žæ€§ä¸ªæ•°ï¼Œç»§ç»­é€’å½’
	    ## items
	        æ•°ç»„
	            æœ‰ç”Ÿæˆè§„åˆ™ï¼š
	                `'name|1': [{}, {} ...]`            å…¶ä¸­ä¹‹ä¸€ï¼Œç»§ç»­é€’å½’
	                `'name|+1': [{}, {} ...]`           é¡ºåºæ£€æµ‹ï¼Œç»§ç»­é€’å½’
	                `'name|min-max': [{}, {} ...]`      æ£€æµ‹ä¸ªæ•°ï¼Œç»§ç»­é€’å½’
	                `'name|count': [{}, {} ...]`        æ£€æµ‹ä¸ªæ•°ï¼Œç»§ç»­é€’å½’
	            æ— ç”Ÿæˆè§„åˆ™ï¼šæ£€æµ‹å…¨éƒ¨çš„å…ƒç´ ä¸ªæ•°ï¼Œç»§ç»­é€’å½’
	*/
	var Diff = {
	    diff: function diff(schema, data, name /* Internal Use Only */ ) {
	        var result = []

	        // å…ˆæ£€æµ‹åç§° name å’Œç±»åž‹ typeï¼Œå¦‚æžœåŒ¹é…ï¼Œæ‰æœ‰å¿…è¦ç»§ç»­æ£€æµ‹
	        if (
	            this.name(schema, data, name, result) &&
	            this.type(schema, data, name, result)
	        ) {
	            this.value(schema, data, name, result)
	            this.properties(schema, data, name, result)
	            this.items(schema, data, name, result)
	        }

	        return result
	    },
	    /* jshint unused:false */
	    name: function(schema, data, name, result) {
	        var length = result.length

	        Assert.equal('name', schema.path, name + '', schema.name + '', result)

	        return result.length === length
	    },
	    type: function(schema, data, name, result) {
	        var length = result.length

	        switch (schema.type) {
	            // è·³è¿‡å«æœ‰ã€Žå ä½ç¬¦ã€çš„å±žæ€§å€¼ï¼Œå› ä¸ºã€Žå ä½ç¬¦ã€è¿”å›žå€¼çš„ç±»åž‹å¯èƒ½å’Œæ¨¡æ¿ä¸ä¸€è‡´ï¼Œä¾‹å¦‚ '@int' ä¼šè¿”å›žä¸€ä¸ªæ•´å½¢å€¼
	            case 'string':
	                if (schema.template.match(Constant.RE_PLACEHOLDER)) return true
	                break
	        }

	        Assert.equal('type', schema.path, Util.type(data), schema.type, result)

	        return result.length === length
	    },
	    value: function(schema, data, name, result) {
	        var length = result.length

	        var rule = schema.rule
	        var templateType = schema.type
	        if (templateType === 'object' || templateType === 'array') return

	        // æ— ç”Ÿæˆè§„åˆ™
	        if (!rule.parameters) {
	            switch (templateType) {
	                case 'regexp':
	                    Assert.match('value', schema.path, data, schema.template, result)
	                    return result.length === length
	                case 'string':
	                    // åŒæ ·è·³è¿‡å«æœ‰ã€Žå ä½ç¬¦ã€çš„å±žæ€§å€¼ï¼Œå› ä¸ºã€Žå ä½ç¬¦ã€çš„è¿”å›žå€¼ä¼šé€šå¸¸ä¼šä¸Žæ¨¡æ¿ä¸ä¸€è‡´
	                    if (schema.template.match(Constant.RE_PLACEHOLDER)) return result.length === length
	                    break
	            }
	            Assert.equal('value', schema.path, data, schema.template, result)
	            return result.length === length
	        }

	        // æœ‰ç”Ÿæˆè§„åˆ™
	        switch (templateType) {
	            case 'number':
	                var parts = (data + '').split('.')
	                parts[0] = +parts[0]

	                // æ•´æ•°éƒ¨åˆ†
	                // |min-max
	                if (rule.min !== undefined && rule.max !== undefined) {
	                    Assert.greaterThanOrEqualTo('value', schema.path, parts[0], rule.min, result)
	                        // , 'numeric instance is lower than the required minimum (minimum: {expected}, found: {actual})')
	                    Assert.lessThanOrEqualTo('value', schema.path, parts[0], rule.max, result)
	                }
	                // |count
	                if (rule.min !== undefined && rule.max === undefined) {
	                    Assert.equal('value', schema.path, parts[0], rule.min, result, '[value] ' + name)
	                }

	                // å°æ•°éƒ¨åˆ†
	                if (rule.decimal) {
	                    // |dmin-dmax
	                    if (rule.dmin !== undefined && rule.dmax !== undefined) {
	                        Assert.greaterThanOrEqualTo('value', schema.path, parts[1].length, rule.dmin, result)
	                        Assert.lessThanOrEqualTo('value', schema.path, parts[1].length, rule.dmax, result)
	                    }
	                    // |dcount
	                    if (rule.dmin !== undefined && rule.dmax === undefined) {
	                        Assert.equal('value', schema.path, parts[1].length, rule.dmin, result)
	                    }
	                }

	                break

	            case 'boolean':
	                break

	            case 'string':
	                // 'aaa'.match(/a/g)
	                var actualRepeatCount = data.match(new RegExp(schema.template, 'g'))
	                actualRepeatCount = actualRepeatCount ? actualRepeatCount.length : actualRepeatCount

	                // |min-max
	                if (rule.min !== undefined && rule.max !== undefined) {
	                    Assert.greaterThanOrEqualTo('repeat count', schema.path, actualRepeatCount, rule.min, result)
	                    Assert.lessThanOrEqualTo('repeat count', schema.path, actualRepeatCount, rule.max, result)
	                }
	                // |count
	                if (rule.min !== undefined && rule.max === undefined) {
	                    Assert.equal('repeat count', schema.path, actualRepeatCount, rule.min, result)
	                }

	                break

	            case 'regexp':
	                var actualRepeatCount = data.match(new RegExp(schema.template.source.replace(/^\^|\$$/g, ''), 'g'))
	                actualRepeatCount = actualRepeatCount ? actualRepeatCount.length : actualRepeatCount

	                // |min-max
	                if (rule.min !== undefined && rule.max !== undefined) {
	                    Assert.greaterThanOrEqualTo('repeat count', schema.path, actualRepeatCount, rule.min, result)
	                    Assert.lessThanOrEqualTo('repeat count', schema.path, actualRepeatCount, rule.max, result)
	                }
	                // |count
	                if (rule.min !== undefined && rule.max === undefined) {
	                    Assert.equal('repeat count', schema.path, actualRepeatCount, rule.min, result)
	                }
	                break
	        }

	        return result.length === length
	    },
	    properties: function(schema, data, name, result) {
	        var length = result.length

	        var rule = schema.rule
	        var keys = Util.keys(data)
	        if (!schema.properties) return

	        // æ— ç”Ÿæˆè§„åˆ™
	        if (!schema.rule.parameters) {
	            Assert.equal('properties length', schema.path, keys.length, schema.properties.length, result)
	        } else {
	            // æœ‰ç”Ÿæˆè§„åˆ™
	            // |min-max
	            if (rule.min !== undefined && rule.max !== undefined) {
	                Assert.greaterThanOrEqualTo('properties length', schema.path, keys.length, rule.min, result)
	                Assert.lessThanOrEqualTo('properties length', schema.path, keys.length, rule.max, result)
	            }
	            // |count
	            if (rule.min !== undefined && rule.max === undefined) {
	                Assert.equal('properties length', schema.path, keys.length, rule.min, result)
	            }
	        }

	        if (result.length !== length) return false

	        for (var i = 0; i < keys.length; i++) {
	            result.push.apply(
	                result,
	                this.diff(
	                    schema.properties[i],
	                    data[keys[i]],
	                    keys[i]
	                )
	            )
	        }

	        return result.length === length
	    },
	    items: function(schema, data, name, result) {
	        var length = result.length

	        if (!schema.items) return

	        var rule = schema.rule

	        // æ— ç”Ÿæˆè§„åˆ™
	        if (!schema.rule.parameters) {
	            Assert.equal('items length', schema.path, data.length, schema.items.length, result)
	        } else {
	            // æœ‰ç”Ÿæˆè§„åˆ™
	            // |min-max
	            if (rule.min !== undefined && rule.max !== undefined) {
	                Assert.greaterThanOrEqualTo('items', schema.path, data.length, (rule.min * schema.items.length), result,
	                    '[{utype}] array is too short: {path} must have at least {expected} elements but instance has {actual} elements')
	                Assert.lessThanOrEqualTo('items', schema.path, data.length, (rule.max * schema.items.length), result,
	                    '[{utype}] array is too long: {path} must have at most {expected} elements but instance has {actual} elements')
	            }
	            // |count
	            if (rule.min !== undefined && rule.max === undefined) {
	                Assert.equal('items length', schema.path, data.length, (rule.min * schema.items.length), result)
	            }
	        }

	        if (result.length !== length) return false

	        for (var i = 0; i < data.length; i++) {
	            result.push.apply(
	                result,
	                this.diff(
	                    schema.items[i % schema.items.length],
	                    data[i],
	                    i % schema.items.length
	                )
	            )
	        }

	        return result.length === length
	    }
	}

	/*
	    å®Œå–„ã€å‹å¥½çš„æç¤ºä¿¡æ¯
	    
	    Equal, not equal to, greater than, less than, greater than or equal to, less than or equal to
	    è·¯å¾„ éªŒè¯ç±»åž‹ æè¿° 

	    Expect path.name is less than or equal to expected, but path.name is actual.

	    Expect path.name is less than or equal to expected, but path.name is actual.
	    Expect path.name is greater than or equal to expected, but path.name is actual.

	*/
	var Assert = {
	    message: function(item) {
	        return (item.message ||
	                '[{utype}] Expect {path}\'{ltype} {action} {expected}, but is {actual}')
	            .replace('{utype}', item.type.toUpperCase())
	            .replace('{ltype}', item.type.toLowerCase())
	            .replace('{path}', Util.isArray(item.path) && item.path.join('.') || item.path)
	            .replace('{action}', item.action)
	            .replace('{expected}', item.expected)
	            .replace('{actual}', item.actual)
	    },
	    equal: function(type, path, actual, expected, result, message) {
	        if (actual === expected) return true
	        switch (type) {
	            case 'type':
	                // æ­£åˆ™æ¨¡æ¿ === å­—ç¬¦ä¸²æœ€ç»ˆå€¼
	                if (expected === 'regexp' && actual === 'string') return true
	                break
	        }

	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is equal to',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    // actual matches expected
	    match: function(type, path, actual, expected, result, message) {
	        if (expected.test(actual)) return true

	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'matches',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    notEqual: function(type, path, actual, expected, result, message) {
	        if (actual !== expected) return true
	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is not equal to',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    greaterThan: function(type, path, actual, expected, result, message) {
	        if (actual > expected) return true
	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is greater than',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    lessThan: function(type, path, actual, expected, result, message) {
	        if (actual < expected) return true
	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is less to',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    greaterThanOrEqualTo: function(type, path, actual, expected, result, message) {
	        if (actual >= expected) return true
	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is greater than or equal to',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    },
	    lessThanOrEqualTo: function(type, path, actual, expected, result, message) {
	        if (actual <= expected) return true
	        var item = {
	            path: path,
	            type: type,
	            actual: actual,
	            expected: expected,
	            action: 'is less than or equal to',
	            message: message
	        }
	        item.message = Assert.message(item)
	        result.push(item)
	        return false
	    }
	}

	valid.Diff = Diff
	valid.Assert = Assert

	module.exports = valid

/***/ },
/* 27 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(28)

/***/ },
/* 28 */
/***/ function(module, exports, __webpack_require__) {

	/* global window, document, location, Event, setTimeout */
	/*
	    ## MockXMLHttpRequest

	    æœŸæœ›çš„åŠŸèƒ½ï¼š
	    1. å®Œæ•´åœ°è¦†ç›–åŽŸç”Ÿ XHR çš„è¡Œä¸º
	    2. å®Œæ•´åœ°æ¨¡æ‹ŸåŽŸç”Ÿ XHR çš„è¡Œä¸º
	    3. åœ¨å‘èµ·è¯·æ±‚æ—¶ï¼Œè‡ªåŠ¨æ£€æµ‹æ˜¯å¦éœ€è¦æ‹¦æˆª
	    4. å¦‚æžœä¸å¿…æ‹¦æˆªï¼Œåˆ™æ‰§è¡ŒåŽŸç”Ÿ XHR çš„è¡Œä¸º
	    5. å¦‚æžœéœ€è¦æ‹¦æˆªï¼Œåˆ™æ‰§è¡Œè™šæ‹Ÿ XHR çš„è¡Œä¸º
	    6. å…¼å®¹ XMLHttpRequest å’Œ ActiveXObject
	        new window.XMLHttpRequest()
	        new window.ActiveXObject("Microsoft.XMLHTTP")

	    å…³é”®æ–¹æ³•çš„é€»è¾‘ï¼š
	    * new   æ­¤æ—¶å°šæ— æ³•ç¡®å®šæ˜¯å¦éœ€è¦æ‹¦æˆªï¼Œæ‰€ä»¥åˆ›å»ºåŽŸç”Ÿ XHR å¯¹è±¡æ˜¯å¿…é¡»çš„ã€‚
	    * open  æ­¤æ—¶å¯ä»¥å–åˆ° URLï¼Œå¯ä»¥å†³å®šæ˜¯å¦è¿›è¡Œæ‹¦æˆªã€‚
	    * send  æ­¤æ—¶å·²ç»ç¡®å®šäº†è¯·æ±‚æ–¹å¼ã€‚

	    è§„èŒƒï¼š
	    http://xhr.spec.whatwg.org/
	    http://www.w3.org/TR/XMLHttpRequest2/

	    å‚è€ƒå®žçŽ°ï¼š
	    https://github.com/philikon/MockHttpRequest/blob/master/lib/mock.js
	    https://github.com/trek/FakeXMLHttpRequest/blob/master/fake_xml_http_request.js
	    https://github.com/ilinsky/xmlhttprequest/blob/master/XMLHttpRequest.js
	    https://github.com/firebug/firebug-lite/blob/master/content/lite/xhr.js
	    https://github.com/thx/RAP/blob/master/lab/rap.plugin.xinglie.js

	    **éœ€ä¸éœ€è¦å…¨é¢é‡å†™ XMLHttpRequestï¼Ÿ**
	        http://xhr.spec.whatwg.org/#interface-xmlhttprequest
	        å…³é”®å±žæ€§ readyStateã€statusã€statusTextã€responseã€responseTextã€responseXML æ˜¯ readonlyï¼Œæ‰€ä»¥ï¼Œè¯•å›¾é€šè¿‡ä¿®æ”¹è¿™äº›çŠ¶æ€ï¼Œæ¥æ¨¡æ‹Ÿå“åº”æ˜¯ä¸å¯è¡Œçš„ã€‚
	        å› æ­¤ï¼Œå”¯ä¸€çš„åŠžæ³•æ˜¯æ¨¡æ‹Ÿæ•´ä¸ª XMLHttpRequestï¼Œå°±åƒ jQuery å¯¹äº‹ä»¶æ¨¡åž‹çš„å°è£…ã€‚

	    // Event handlers
	    onloadstart         loadstart
	    onprogress          progress
	    onabort             abort
	    onerror             error
	    onload              load
	    ontimeout           timeout
	    onloadend           loadend
	    onreadystatechange  readystatechange
	 */

	var Util = __webpack_require__(3)

	// å¤‡ä»½åŽŸç”Ÿ XMLHttpRequest
	window._XMLHttpRequest = window.XMLHttpRequest
	window._ActiveXObject = window.ActiveXObject

	/*
	    PhantomJS
	    TypeError: '[object EventConstructor]' is not a constructor (evaluating 'new Event("readystatechange")')

	    https://github.com/bluerail/twitter-bootstrap-rails-confirm/issues/18
	    https://github.com/ariya/phantomjs/issues/11289
	*/
	try {
	    new window.Event('custom')
	} catch (exception) {
	    window.Event = function(type, bubbles, cancelable, detail) {
	        var event = document.createEvent('CustomEvent') // MUST be 'CustomEvent'
	        event.initCustomEvent(type, bubbles, cancelable, detail)
	        return event
	    }
	}

	var XHR_STATES = {
	    // The object has been constructed.
	    UNSENT: 0,
	    // The open() method has been successfully invoked.
	    OPENED: 1,
	    // All redirects (if any) have been followed and all HTTP headers of the response have been received.
	    HEADERS_RECEIVED: 2,
	    // The response's body is being received.
	    LOADING: 3,
	    // The data transfer has been completed or something went wrong during the transfer (e.g. infinite redirects).
	    DONE: 4
	}

	var XHR_EVENTS = 'readystatechange loadstart progress abort error load timeout loadend'.split(' ')
	var XHR_REQUEST_PROPERTIES = 'timeout withCredentials'.split(' ')
	var XHR_RESPONSE_PROPERTIES = 'readyState responseURL status statusText responseType response responseText responseXML'.split(' ')

	// https://github.com/trek/FakeXMLHttpRequest/blob/master/fake_xml_http_request.js#L32
	var HTTP_STATUS_CODES = {
	    100: "Continue",
	    101: "Switching Protocols",
	    200: "OK",
	    201: "Created",
	    202: "Accepted",
	    203: "Non-Authoritative Information",
	    204: "No Content",
	    205: "Reset Content",
	    206: "Partial Content",
	    300: "Multiple Choice",
	    301: "Moved Permanently",
	    302: "Found",
	    303: "See Other",
	    304: "Not Modified",
	    305: "Use Proxy",
	    307: "Temporary Redirect",
	    400: "Bad Request",
	    401: "Unauthorized",
	    402: "Payment Required",
	    403: "Forbidden",
	    404: "Not Found",
	    405: "Method Not Allowed",
	    406: "Not Acceptable",
	    407: "Proxy Authentication Required",
	    408: "Request Timeout",
	    409: "Conflict",
	    410: "Gone",
	    411: "Length Required",
	    412: "Precondition Failed",
	    413: "Request Entity Too Large",
	    414: "Request-URI Too Long",
	    415: "Unsupported Media Type",
	    416: "Requested Range Not Satisfiable",
	    417: "Expectation Failed",
	    422: "Unprocessable Entity",
	    500: "Internal Server Error",
	    501: "Not Implemented",
	    502: "Bad Gateway",
	    503: "Service Unavailable",
	    504: "Gateway Timeout",
	    505: "HTTP Version Not Supported"
	}

	/*
	    MockXMLHttpRequest
	*/

	function MockXMLHttpRequest() {
	    // åˆå§‹åŒ– custom å¯¹è±¡ï¼Œç”¨äºŽå­˜å‚¨è‡ªå®šä¹‰å±žæ€§
	    this.custom = {
	        events: {},
	        requestHeaders: {},
	        responseHeaders: {}
	    }
	}

	MockXMLHttpRequest._settings = {
	    timeout: '10-100',
	    /*
	        timeout: 50,
	        timeout: '10-100',
	     */
	}

	MockXMLHttpRequest.setup = function(settings) {
	    Util.extend(MockXMLHttpRequest._settings, settings)
	    return MockXMLHttpRequest._settings
	}

	Util.extend(MockXMLHttpRequest, XHR_STATES)
	Util.extend(MockXMLHttpRequest.prototype, XHR_STATES)

	// æ ‡è®°å½“å‰å¯¹è±¡ä¸º MockXMLHttpRequest
	MockXMLHttpRequest.prototype.mock = true

	// æ˜¯å¦æ‹¦æˆª Ajax è¯·æ±‚
	MockXMLHttpRequest.prototype.match = false

	// åˆå§‹åŒ– Request ç›¸å…³çš„å±žæ€§å’Œæ–¹æ³•
	Util.extend(MockXMLHttpRequest.prototype, {
	    // https://xhr.spec.whatwg.org/#the-open()-method
	    // Sets the request method, request URL, and synchronous flag.
	    open: function(method, url, async, username, password) {
	        var that = this

	        Util.extend(this.custom, {
	            method: method,
	            url: url,
	            async: typeof async === 'boolean' ? async : true,
	            username: username,
	            password: password,
	            options: {
	                url: url,
	                type: method
	            }
	        })

	        this.custom.timeout = function(timeout) {
	            if (typeof timeout === 'number') return timeout
	            if (typeof timeout === 'string' && !~timeout.indexOf('-')) return parseInt(timeout, 10)
	            if (typeof timeout === 'string' && ~timeout.indexOf('-')) {
	                var tmp = timeout.split('-')
	                var min = parseInt(tmp[0], 10)
	                var max = parseInt(tmp[1], 10)
	                return Math.round(Math.random() * (max - min)) + min
	            }
	        }(MockXMLHttpRequest._settings.timeout)

	        // æŸ¥æ‰¾ä¸Žè¯·æ±‚å‚æ•°åŒ¹é…çš„æ•°æ®æ¨¡æ¿
	        var item = find(this.custom.options)

	        function handle(event) {
	            // åŒæ­¥å±žæ€§ NativeXMLHttpRequest => MockXMLHttpRequest
	            for (var i = 0; i < XHR_RESPONSE_PROPERTIES.length; i++) {
	                try {
	                    that[XHR_RESPONSE_PROPERTIES[i]] = xhr[XHR_RESPONSE_PROPERTIES[i]]
	                } catch (e) {}
	            }
	            // è§¦å‘ MockXMLHttpRequest ä¸Šçš„åŒåäº‹ä»¶
	            that.dispatchEvent(new Event(event.type /*, false, false, that*/ ))
	        }

	        // å¦‚æžœæœªæ‰¾åˆ°åŒ¹é…çš„æ•°æ®æ¨¡æ¿ï¼Œåˆ™é‡‡ç”¨åŽŸç”Ÿ XHR å‘é€è¯·æ±‚ã€‚
	        if (!item) {
	            // åˆ›å»ºåŽŸç”Ÿ XHR å¯¹è±¡ï¼Œè°ƒç”¨åŽŸç”Ÿ open()ï¼Œç›‘å¬æ‰€æœ‰åŽŸç”Ÿäº‹ä»¶
	            var xhr = createNativeXMLHttpRequest()
	            this.custom.xhr = xhr

	            // åˆå§‹åŒ–æ‰€æœ‰äº‹ä»¶ï¼Œç”¨äºŽç›‘å¬åŽŸç”Ÿ XHR å¯¹è±¡çš„äº‹ä»¶
	            for (var i = 0; i < XHR_EVENTS.length; i++) {
	                xhr.addEventListener(XHR_EVENTS[i], handle)
	            }

	            // xhr.open()
	            if (username) xhr.open(method, url, async, username, password)
	            else xhr.open(method, url, async)

	            // åŒæ­¥å±žæ€§ MockXMLHttpRequest => NativeXMLHttpRequest
	            for (var j = 0; j < XHR_REQUEST_PROPERTIES.length; j++) {
	                try {
	                    xhr[XHR_REQUEST_PROPERTIES[j]] = that[XHR_REQUEST_PROPERTIES[j]]
	                } catch (e) {}
	            }

	            return
	        }

	        // æ‰¾åˆ°äº†åŒ¹é…çš„æ•°æ®æ¨¡æ¿ï¼Œå¼€å§‹æ‹¦æˆª XHR è¯·æ±‚
	        this.match = true
	        this.custom.template = item
	        this.readyState = MockXMLHttpRequest.OPENED
	        this.dispatchEvent(new Event('readystatechange' /*, false, false, this*/ ))
	    },
	    // https://xhr.spec.whatwg.org/#the-setrequestheader()-method
	    // Combines a header in author request headers.
	    setRequestHeader: function(name, value) {
	        // åŽŸç”Ÿ XHR
	        if (!this.match) {
	            this.custom.xhr.setRequestHeader(name, value)
	            return
	        }

	        // æ‹¦æˆª XHR
	        var requestHeaders = this.custom.requestHeaders
	        if (requestHeaders[name]) requestHeaders[name] += ',' + value
	        else requestHeaders[name] = value
	    },
	    timeout: 0,
	    withCredentials: false,
	    upload: {},
	    // https://xhr.spec.whatwg.org/#the-send()-method
	    // Initiates the request.
	    send: function send(data) {
	        var that = this
	        this.custom.options.body = data

	        // åŽŸç”Ÿ XHR
	        if (!this.match) {
	            this.custom.xhr.send(data)
	            return
	        }

	        // æ‹¦æˆª XHR

	        // X-Requested-With header
	        this.setRequestHeader('X-Requested-With', 'MockXMLHttpRequest')

	        // loadstart The fetch initiates.
	        this.dispatchEvent(new Event('loadstart' /*, false, false, this*/ ))

	        if (this.custom.async) setTimeout(done, this.custom.timeout) // å¼‚æ­¥
	        else done() // åŒæ­¥

	        function done() {
	            that.readyState = MockXMLHttpRequest.HEADERS_RECEIVED
	            that.dispatchEvent(new Event('readystatechange' /*, false, false, that*/ ))
	            that.readyState = MockXMLHttpRequest.LOADING
	            that.dispatchEvent(new Event('readystatechange' /*, false, false, that*/ ))

	            that.status = 200
	            that.statusText = HTTP_STATUS_CODES[200]

	            // fix #92 #93 by @qddegtya
	            that.response = that.responseText = JSON.stringify(
	                convert(that.custom.template, that.custom.options),
	                null, 4
	            )

	            that.readyState = MockXMLHttpRequest.DONE
	            that.dispatchEvent(new Event('readystatechange' /*, false, false, that*/ ))
	            that.dispatchEvent(new Event('load' /*, false, false, that*/ ));
	            that.dispatchEvent(new Event('loadend' /*, false, false, that*/ ));
	        }
	    },
	    // https://xhr.spec.whatwg.org/#the-abort()-method
	    // Cancels any network activity.
	    abort: function abort() {
	        // åŽŸç”Ÿ XHR
	        if (!this.match) {
	            this.custom.xhr.abort()
	            return
	        }

	        // æ‹¦æˆª XHR
	        this.readyState = MockXMLHttpRequest.UNSENT
	        this.dispatchEvent(new Event('abort', false, false, this))
	        this.dispatchEvent(new Event('error', false, false, this))
	    }
	})

	// åˆå§‹åŒ– Response ç›¸å…³çš„å±žæ€§å’Œæ–¹æ³•
	Util.extend(MockXMLHttpRequest.prototype, {
	    responseURL: '',
	    status: MockXMLHttpRequest.UNSENT,
	    statusText: '',
	    // https://xhr.spec.whatwg.org/#the-getresponseheader()-method
	    getResponseHeader: function(name) {
	        // åŽŸç”Ÿ XHR
	        if (!this.match) {
	            return this.custom.xhr.getResponseHeader(name)
	        }

	        // æ‹¦æˆª XHR
	        return this.custom.responseHeaders[name.toLowerCase()]
	    },
	    // https://xhr.spec.whatwg.org/#the-getallresponseheaders()-method
	    // http://www.utf8-chartable.de/
	    getAllResponseHeaders: function() {
	        // åŽŸç”Ÿ XHR
	        if (!this.match) {
	            return this.custom.xhr.getAllResponseHeaders()
	        }

	        // æ‹¦æˆª XHR
	        var responseHeaders = this.custom.responseHeaders
	        var headers = ''
	        for (var h in responseHeaders) {
	            if (!responseHeaders.hasOwnProperty(h)) continue
	            headers += h + ': ' + responseHeaders[h] + '\r\n'
	        }
	        return headers
	    },
	    overrideMimeType: function( /*mime*/ ) {},
	    responseType: '', // '', 'text', 'arraybuffer', 'blob', 'document', 'json'
	    response: null,
	    responseText: '',
	    responseXML: null
	})

	// EventTarget
	Util.extend(MockXMLHttpRequest.prototype, {
	    addEventListene: function addEventListene(type, handle) {
	        var events = this.custom.events
	        if (!events[type]) events[type] = []
	        events[type].push(handle)
	    },
	    removeEventListener: function removeEventListener(type, handle) {
	        var handles = this.custom.events[type] || []
	        for (var i = 0; i < handles.length; i++) {
	            if (handles[i] === handle) {
	                handles.splice(i--, 1)
	            }
	        }
	    },
	    dispatchEvent: function dispatchEvent(event) {
	        var handles = this.custom.events[event.type] || []
	        for (var i = 0; i < handles.length; i++) {
	            handles[i].call(this, event)
	        }

	        var ontype = 'on' + event.type
	        if (this[ontype]) this[ontype](event)
	    }
	})

	// Inspired by jQuery
	function createNativeXMLHttpRequest() {
	    var isLocal = function() {
	        var rlocalProtocol = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/
	        var rurl = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/
	        var ajaxLocation = location.href
	        var ajaxLocParts = rurl.exec(ajaxLocation.toLowerCase()) || []
	        return rlocalProtocol.test(ajaxLocParts[1])
	    }()

	    return window.ActiveXObject ?
	        (!isLocal && createStandardXHR() || createActiveXHR()) : createStandardXHR()

	    function createStandardXHR() {
	        try {
	            return new window._XMLHttpRequest();
	        } catch (e) {}
	    }

	    function createActiveXHR() {
	        try {
	            return new window._ActiveXObject("Microsoft.XMLHTTP");
	        } catch (e) {}
	    }
	}


	// æŸ¥æ‰¾ä¸Žè¯·æ±‚å‚æ•°åŒ¹é…çš„æ•°æ®æ¨¡æ¿ï¼šURLï¼ŒType
	function find(options) {

	    for (var sUrlType in MockXMLHttpRequest.Mock._mocked) {
	        var item = MockXMLHttpRequest.Mock._mocked[sUrlType]
	        if (
	            (!item.rurl || match(item.rurl, options.url)) &&
	            (!item.rtype || match(item.rtype, options.type.toLowerCase()))
	        ) {
	            // console.log('[mock]', options.url, '>', item.rurl)
	            return item
	        }
	    }

	    function match(expected, actual) {
	        if (Util.type(expected) === 'string') {
	            return expected === actual
	        }
	        if (Util.type(expected) === 'regexp') {
	            return expected.test(actual)
	        }
	    }

	}

	// æ•°æ®æ¨¡æ¿ ï¼> å“åº”æ•°æ®
	function convert(item, options) {
	    return Util.isFunction(item.template) ?
	        item.template(options) : MockXMLHttpRequest.Mock.mock(item.template)
	}

	module.exports = MockXMLHttpRequest

/***/ }
/******/ ])
});
;