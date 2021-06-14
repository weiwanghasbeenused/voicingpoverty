function toggle_block(el, isBlock = true){
	var this_block = el;
	if( !this_block.classList.contains('block')){
		isBlock = false;
		this_block = this_block.parentNode;
		while( !this_block.classList.contains('block'))
			this_block = this_block.parentNode;
	}
	if( !this_block.classList.contains('expanded') )
		this_block.classList.add('expanded');
	else
	{
		if( !isBlock )
			this_block.classList.remove('expanded');
	}
}