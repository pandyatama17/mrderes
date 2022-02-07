$.fn.materialize_select = function(settings){
	if(!settings) settings = {};
	return this.each(function(){
		return $.fn.materialize_select.module.call($(this),settings);
	});
};

$.fn.materialize_select.module = function(settings){
	/*
	 * GLOBALS
	 */
	var select = this, selected, multiple, ajax, wrapper, caret, trigger, dropdown, dropdownOpen, placeholder;

	function init()
	{
		if(select.hasClass('materialize_select_processed')) return;
		else select.addClass('materialize_select_processed');

		if(settings.debug)
		{
			select.show();
			select.height(500);
			select.css('margin-top','300px');
		}

		selected = [];
		dropdownOpen = false;
		multiple = (select.attr('multiple')) ? true : false;
		ajax = select.attr('ajax') || null;
		placeholder = select.attr('placeholder') || ( multiple ? 'Select Multiple' : 'Select One' );
		wrapper = $('<div class="select-wrapper"></div>');
		caret = $('<span class="caret">&#9660;</span>');
		trigger = $('<input></input>');
		trigger.addClass('select-dropdown');
		trigger.attr('placeholder',placeholder);
		dropdown = $('<ul></ul>');
		wrapper.insertAfter(select);
		wrapper.append(caret).append(trigger).append(dropdown).append(select);
		dropdown.addClass('dropdown-content select-dropdown');

		initEvents();
		return load();
	}

	function load(callback)
	{
		loadOptionsFromSelect();
		if(ajax)
			return prefetchAjax(callback);
		else 
			return updateTrigger(callback);
	}

	function loadOptionsFromSelect()
	{
		selected = [];
		select.find('option').each(function(){
			selected.push({
				value: $(this).attr('value'),
				text: $(this).html(),
				selected: $(this).is(':selected')
			});
		});
	}

	function getAllOptions(field)
	{
		if(!field)
			return selected;
		var vals = [];
		for(var i=0;i<selected.length;i++)
			vals.push(selected[i][field]);
		return vals;
	}

	function getSelectedOptions(field)
	{
		var vals = [];
		for(var i=0;i<selected.length;i++)
		{
			if(selected[i].selected)
			{
				if(field)
					vals.push(selected[i][field]);
				else
					vals.push(selected[i]);
			}
		}
		return vals;
	}

	function prefetchAjax(callback)
	{
		$.ajax({
			url: ajax,
			type:"POST",
			data: JSON.stringify({
				action: 'prefetch',
				values: getSelectedOptions('value')
			}),
			contentType:"application/json; charset=utf-8",
			dataType:"json",
			success: function(result){
				for(var i=0;i<result.length;i++)
					result[i].selected = true;
				selected = result;
				updateTrigger(callback);
			}
		});
	}

	function changeSelected(option,action)
	{
		var found = false;
		for(var i=0;i<selected.length;i++)
		{
			if(!multiple) selected[i].selected = false;
			if(selected[i].value == option.value)
			{
				found = true;
				if(action == 'add')
				{
					selected[i].selected = true;
				}
				else if(action == 'remove') 
				{
					selected[i].selected = false;
				}
			}
		}

		if(!found && ajax)
		{
			if(action == 'add')
				option.selected = true;
			else if(action == 'remove')
				option.selected = false;
			selected.push(option);
		}

		updateTrigger();
		updateSelect((!found) ? option : null );
	}

	function updateTrigger(callback)
	{
		trigger.val(getSelectedOptions('text').join(', '));
		if(callback) return callback();
	}

	function updateSelect(newOption)
	{
		if(newOption)
		{
			var option = $('<option></option>')
				.attr('value',newOption.value)
				.html(newOption.text);
			select.append(option);
		}

		select.val(getSelectedOptions('value'));
		select.trigger('change',[true]);
		select.trigger('input',[true]);
	}

	function populateDropdown()
	{
		dropdown.find('li.selector').remove();
		if(dropdown.children().length == 0)
		{
			dropdown
				.append('<div class="material-icons close">close</div>')
				.append('<div class="search"><input type="text" class="search-input"></input></div>')
				.find('.search-input').attr('placeholder',placeholder);
		}

		if(trigger.val())
			dropdown.find('.search-input').attr('placeholder',trigger.val());
		else 
			dropdown.find('.search-input').attr('placeholder',placeholder);

		var options = getAllOptions();
		for(var i=0;i<options.length;i++)
		{
			var option = options[i];

			if(ajax && !option.selected) continue;

			var li = $('<li class="selector"></li>');

			if(multiple)
				li.html('<span><input type="checkbox"><label></label>'+option.text+'</span>');
			else 
				li.html('<span>'+option.text+'</span>');
			
			if(option.selected)
			{
				li.addClass('active');
				li.find('input').prop('checked',true);
			}
			li.data('option',option);
			dropdown.append(li);
		}	

		dropdown.find('.selector.active:eq(0)').addClass('selected');
	}

	function populateDummyDropdown(results)
	{
		dropdown.find('li.selector').remove();
		for(var i=0;i<results.length;i++)
		{
			var option = results[i];

			var li = $('<li class="selector"></li>');

			if(multiple)
				li.html('<span><input type="checkbox"><label></label>'+option.text+'</span>');
			else 
				li.html('<span>'+option.text+'</span>');
			
			if(option.selected)
			{
				li.addClass('active');
				li.find('input').prop('checked',true);
			}

			li.data('option',option);
			dropdown.append(li);
		}	
	}

	function showDropdown()
	{
		if(dropdownOpen) return closeDropdown();

		var margin = parseFloat(getComputedStyle(dropdown[0]).fontSize) * 0.75;
		if(!margin)margin = 10;

		dropdown.width(wrapper.width()+ (margin*2) );
		dropdown.css({
			opacity: 0,
			top: 0,
			left: -margin + 'px'
		});
		dropdown.velocity("slideDown",{duration: 300,queue: false}).velocity({opacity:1},{duration: 300,queue: false,complete:function(){
			return dropdown.find('.search-input').focus();
		}});
		dropdownOpen = true;
		return windowHideDropdown();
	}

	function windowHideDropdown() 
	{
		$(document).one('tap',function(e){
			if(!dropdownOpen) return;
			if($(e.target).closest(dropdown).length)
				return windowHideDropdown();
			return closeDropdown();
		});
	}

	function closeDropdown()
	{
		dropdown.velocity("slideUp",{duration: 225,queue: false}).velocity({opacity:0},{duration: 225,queue: false});
		dropdown.find('.search-input').val('');
		dropdownOpen = false;
	}

	function searchAjax(search)
	{
		if(search)
		{
			return $.ajax({
				url: ajax,
				type:"POST",
				data: JSON.stringify({
					action: 'search',
					search: search
				}),
				contentType:"application/json; charset=utf-8",
				dataType:"json",
				success: function(results){
					return populateDummyDropdown(results);
				}
			});
		}
		else 
		{
			return populateDropdown();
		}
	}

	function searchInternal(search)
	{
		if(search)
		{
			dropdown.find('.selector').each(function(){
				var text = $(this).data('option').text.toLowerCase();
				if(text.indexOf(search) >= 0)
					$(this).show();
				else 
					$(this).hide();
			});
		}
		else 
		{
			dropdown.find('.selector').show();
		}
	}

	function initEvents()
	{
		trigger.on('tapstart',function(e){
			e.preventDefault();
			e.stopPropagation();
			return trigger.blur();
		});

		trigger.on('tap',function(e,touch){
			e.preventDefault();
			e.stopPropagation();
			trigger.blur();
			dropdown.addClass('active');
			populateDropdown();
			return showDropdown();
		});

		dropdown.on('tap','.selector',function(e,touch){
			var option = $(this).data('option'),
				li = $(this),
				action;

			if(!multiple)
			{
				changeSelected(option,'add');
				return closeDropdown();
			}
			
			dropdown.find('.selector.selected').removeClass('selected');
			if(li.is('.active'))
			{
				action = 'remove';
				li.removeClass('active');
				li.find('input').prop('checked',false);
			}
			else 
			{
				action = 'add';
				li.addClass('active');
				li.find('input').prop('checked',true);
				li.addClass('selected');
			}
			return changeSelected(option,action);
		});

		dropdown.on('keyup','.search-input',function(e){
			var search = $(this).val().toLowerCase();
			if(ajax)
				return searchAjax(search);
			else 
				return searchInternal(search);
		});

		dropdown.on('tap','.close',function(e){
			return closeDropdown();
		});

		select.on('change input',function(e,internal){
			if(internal) return;
			return load(function(){
				return dropdown.find('.search-input').trigger('keyup');
			});
		});
	}

	return init();
};

$(document).ready(function(){
	$('#a').materialize_select();
});