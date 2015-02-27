/*!
 * file upload
 * xiewulong <xiewulong@vip.qq.com>
 * create: 2015/2/27
 * version: 0.0.1
 */

(function($, window, document, undefined){
	var fileupload	= function(input, fn, prop){
			prop = prop || {};
			this.input = input;
			this.name = input.name;
			this.$input = $(input);
			this.$parent = this.$input.parent();
			this.action = prop.action || this.$input.attr('data-action');
			this.min = prop.min || this.$input.attr('data-min');
			this.max = prop.max || this.$input.attr('data-max');
			this.type = prop.type || this.$input.attr('data-type');
			this.sizes = prop.sizes || this.$input.attr('data-sizes');
			this.csrf = prop.csrf || this.$input.attr('data-csrf');
			this.pre = prop.pre || 'xFileupload';
			this.before = prop.before || function(){};
			this.fn = fn;

			this.action && this.fn && this.init();
		}
	
	fileupload.prototype = {
		init: function(){
			var _this = this;
			this.$input.on('change', function(){
				if(this.value == '')return;
				_this.setName();
				_this.createElements();
				window[_this.input.name] = function(d){_this.callback(d);};
				_this.before && _this.before.call(_this.input, _this.name);
				_this.$form.submit();
			});
		},
		createElements: function(){
			this.$iframe = $('<iframe style="display:none;" name="' + this.input.name + '"></iframe>').appendTo('body');
			this.$form = $('<form style="display:none;" action="' + this.action + '" target="' + this.input.name + '" method="post" enctype="multipart/form-data"></form>').appendTo('body');
			this.min && this.$form.append('<input type="hidden" name="min" value="' + this.min + '" />');
			this.max && this.$form.append('<input type="hidden" name="max" value="' + this.max + '" />');
			this.type && this.$form.append('<input type="hidden" name="type" value="' + this.type + '" />');
			this.sizes && this.$form.append('<input type="hidden" name="sizes" value="' + this.sizes + '" />');
			this.csrf && this.$form.append('<input type="hidden" name="_csrf" value="' + this.csrf + '" />');
			this.$form.append('<input type="hidden" name="name" value="' + this.input.name + '" />');
			this.$form.append(this.input);
		},
		setName: function(){
			var random = (+ new Date()).toString() + Math.floor(Math.random() * 899 + 100).toString();
			this.input.name = this.pre + random;
		},
		callback: function(d){
			this.input.name = this.name;
			this.$parent.append(this.input);
			this.$form.remove();
			this.$iframe.remove();
			this.fn.call(this.input, d);
		},
	}

	$.fn.fileupload = function(fn, prop){
		return this.each(function(){
			new fileupload(this, fn, prop);
		});
	};
})(jQuery, window, document);