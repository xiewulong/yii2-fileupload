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
			this.oss = prop.oss || this.$input.attr('data-oss');
			this.csrf = prop.csrf || this.$input.attr('data-csrf');
			this.pre = prop.pre || 'xFileupload';
			this.before = prop.before || function(){};
			this.fn = fn;

			this.input.value && this.action && this.fn && this.upload();
		};
	
	fileupload.prototype = {
		upload: function(){
			var _this = this;
			this.setName();
			this.createElements();
			window[_this.input.name] = function(d){_this.callback(d);};
			this.before && this.before.call(this.input, this.name);
			this.$form.submit();
		},
		createElements: function(){
			this.$iframe = $('<iframe style="display:none;" name="' + this.input.name + '"></iframe>').appendTo('body');
			this.$form = $('<form style="display:none;" action="' + this.action + '" target="' + this.input.name + '" method="post" enctype="multipart/form-data"></form>').appendTo('body');
			this.min && this.$form.append('<input type="hidden" name="min" value="' + this.min + '" />');
			this.max && this.$form.append('<input type="hidden" name="max" value="' + this.max + '" />');
			this.type && this.$form.append('<input type="hidden" name="type" value="' + this.type + '" />');
			this.sizes && this.$form.append('<input type="hidden" name="sizes" value="' + this.sizes + '" />');
			this.oss && this.$form.append('<input type="hidden" name="oss" value="' + this.oss + '" />');
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
			this.input.value = '';
			this.$parent.append(this.input);
			this.$form.remove();
			this.$iframe.remove();
			this.fn.call(this.input, d);
		}
	}

	$(document).on('change', '[data-fileupload]', function(){
		new fileupload(this, function(d){
			$(this).trigger('uploaded.x.file', d);
		}, {'before': function(name){
			$(this).trigger('upload.x.file', name);
		}});
	});

	$.fn.fileupload = function(fn, prop){
		return this.on('change', function(){
			new fileupload(this, fn, prop);
		});
	};
})(jQuery, window, document);