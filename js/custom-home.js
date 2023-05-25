function toggle_tab(el){
	if( !el.classList.contains('active') )
	{
		var current_active = document.querySelector('.tab.active');
		if(current_active != null)
			current_active.classList.remove('active');
		var current_viewing = document.querySelector('.tab-content.viewing');
		if(current_viewing != null)
			current_viewing.classList.remove('viewing');
		var this_tab_name = el.getAttribute('tab');
		var this_tab_content = document.getElementById('tab-content_'+this_tab_name);
		this_tab_content.classList.add('viewing');
		el.classList.add('active');
	}
}
function next_slide(el){
	var viewing = el.querySelector('.blocks-gallery-item.viewing');
	if(viewing != null)
	{
		viewing.classList.remove('viewing');
		var nextSlide = viewing.nextSibling;
		if(nextSlide == null)
			nextSlide = viewing.parentNode.firstChild;
		nextSlide.classList.add('viewing');
	}
}
function prev_slide(el){
	var viewing = el.querySelector('.blocks-gallery-item.viewing');
	if(viewing != null)
	{
		viewing.classList.remove('viewing');
		var nextSlide = viewing.previousSibling;
		if(nextSlide == null)
			nextSlide = viewing.parentNode.lastChild;
		nextSlide.classList.add('viewing');
	}
}

/* add classes that hides elements */







