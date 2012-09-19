/**
 * @author Jose Manuel
 */

//Common operations

google.load("language", "1");

$(document).ready(function(){
	$("table td:first").css('width', '400px');
	$("#add_key").click(function(){
		$("#save_items table tr:first").after("<tr><td><input type='text' value='NAME' class='newfield' onchange='setname(this);' /></td><td><input type='text' class='newfield value' value='VALUE' /></td></tr>");
		
		var el =  '<a onclick="switch_editor(this)" title="switch beetween xhtml/plain text" href="javascript:void(0);"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHlSURBVBgZpcE7a1RRFIbh95yZXEaSSLwWFkFEkICKhWhhIV7AxlKsbSz9DQpa+gfsbERQsUhnEYOFFiJoYSrBO6IBY5I5c2bvtfb6jCIIYjfPU0liFDUjqhlR99r9FfEfHoFZkNwxg9ZFm5xkTptFY0HbOl02Hdvf4y/hIUoRHsKLMBcWgZkwD6wE2YNbi1/p8sf6wCkBHsJLkIswD8xF9iB5IZtIHmQLtk11aftOl03nDk/x6NUGpw9OsTYo3H26yoXjs/TGK8Qmwav3A5aW17h0cjfJg9tL34jWqJM7gxTMTnWIgImxmjYXeuMVNxe+UAFX731kbuc483t67Nk+zt5dk7QWROPUTXKevWk4um8LD5+vMjlWcfnMTrqdin4qCGhSIQJOHJjhl41hIVlBTaHut+LU/DSPX69z9tAMgxTcePCZZKIZFiRohoWQePmuz4eVhARDE5Ey9VqbsSKeLK/TqSsk6CdHEk0qIGhyIQQ3Fz7xY+Bs7XW4fnEOJVGdvr6s80dm+fQ9kS1IHiQT2YPkQfbAPDAXVgIrwkPM7Zhg8c5buusbTpsL05Md8ljFpFXYhHCvMK+xEFZEKYEHlAgkkPit2nflhYatIxORAmVHFigVyIFKIAvkggj+VUliFDUj+gngimmFTeOsxAAAAABJRU5ErkJggg=="> </a>';
		
		$('#save_items table tr.newfield input.value').after(el)
		return false;
	});
	$("#reset_form").click(function(){
		$("#save_items .newfield").parent().parent().remove();
	});

	$('#save_items').submit(function(){
		/*$.each($('#save_items input:text, #save_items textarea'), function(){
			var replaced_text = $(this).val().replace(/%((\d|[a-f,A-F]){2})/g,"%/$1");
			$(this).val(replaced_text);
			console.log($(this).val());
		});*/
		
		//sym.posium backend
		if(opener && opener.reload_news_select){
			opener.reload_news_select();
		}
		
	});

});
function setname(o){
	$("input", $(o).parent().next()).attr("name", $(o).val());
}

var from_language = "en";
var to_language;
var textfield;

function gtranslate(o){
	from_language = "en";

	to_language = $('#save_items input[name=language]').val();
	textfield = $("input, textarea", $(o).parent().next());

      google.language.detect(textfield.val(), function(result) {
        if (!result.error && result.isReliable) {
		    from_language = result.language;

			google.language.translate(textfield.val(), from_language, to_language, function(result) {
		        if (!result.error) {
					translate_result = result.translation;
					if (typeof textfield[0].oldValue == "undefined"){
						textfield[0].oldValue = textfield.val();
					}

					textfield.val( translate_result );

		        }else{
					alert("Sorry, Google can not translate this field");
					return false;
				}
		      });
        }else{
			from_language = window.prompt('Please write the origin language of the field, ex.: en, es,...');
			google.language.translate(textfield.val(), from_language, to_language, function(result) {
		        if (!result.error) {
					translate_result = result.translation;
					if (typeof textfield[0].oldValue == "undefined"){
						textfield[0].oldValue = textfield.val();
					}

					textfield.val( translate_result );

		        }else{
					alert("Sorry, Google can not translate this field");
					return false;
				}
		      });
		}
      });

	return false;
}

function gtranslate_revert(o){
	var textfield = $("input, textarea", $(o).parent().next());

	if(typeof textfield[0].oldValue != "undefined"){
		textfield.val(textfield[0].oldValue);
		delete textfield[0].oldValue;
	}else{
		from_language = window.prompt('Please write the origin language of the field, ex.: en, es,...');
		file = $("#save_items input[name=file]").val();
		$.getJSON("/language_editor/get_line?line="+textfield[0].name+"&file="+file+"&language="+from_language, function(datos){
			textfield.val(datos.result);
		});
	}
	return false;
}

function remove_me(o){
	$("input, textarea", $(o).parent().next()).val("XXXREMOVEXXX");
}

function switch_editor(o)
{
	if(!$(o).parent().parent().hasClass('newfield'))
	{
		//switch from ckeditor to plain text
		if($(o).parent().parent().children('td:eq(1)').children('span').length > 0)
		{
			var form_element = $(o).parent().parent().children('td:eq(1)').children('input:text, textarea');
			form_element.ckeditorGet().destroy(); 
		}
		//switch from plain text to ckeditor
		else
		{
			var form_element = $(o).parent().parent().children('td:eq(1)').children(); 
			if(form_element.attr('type') != 'textarea')
			{
				form_element.replaceWith($('<textarea></textarea>').val(form_element.val()).attr({
					id: form_element.attr('id'),
					name: form_element.attr('name'),
					rows: 12,
					cols: 90
				}));
			}
			var form_element = $(o).parent().parent().children('td:eq(1)').children(); 
			form_element.ckeditor();
		}
	}
	return false;
}
