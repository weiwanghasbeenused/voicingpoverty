var wW = window.innerWidth;
var wH = window.innerHeight;
var body=document.body;

function toggle_block(el, isBlock = true, isAll = false){
	if(isAll)
	{
		var blocks = document.querySelectorAll('.section-block.foldable');
		var isViewingAll = el.getAttribute('isViewingAll');
		if(isViewingAll == 'false')
		{
			[].forEach.call(blocks, function(el, i){
				el.classList.add('expanded');
			});
			el.setAttribute('isViewingAll', true);
			el.innerText = 'Hide All';
		}
		else
		{
			[].forEach.call(blocks, function(el, i){
				el.classList.remove('expanded');
			});
			el.setAttribute('isViewingAll', false);
			el.innerText = 'View All';
		}
		
	}
	else
	{
		var this_block = el;
		var transition_duration = 500;
		if( !this_block.classList.contains('block')){
			isBlock = false;
			this_block = this_block.parentNode;
			while( !this_block.classList.contains('block'))
				this_block = this_block.parentNode;
		}
		if( !this_block.classList.contains('expanded') ){
			this_block.classList.add('expanded');
			this_block.classList.add('transition');
			setTimeout(function(){
				this_block.classList.remove('transition');
			}, transition_duration);
		}
		else
		{
			if( !isBlock )
				this_block.classList.remove('expanded');
		}
	}
}
function toggle_read_more_block(el, isBlock = true, isAll = false){
	el.classList.toggle('expanded');
}
function toggle_listItem(el){
	var this_item = el.parentNode;
	if( !this_item.classList.contains('list-item')){
		this_item = this_item.parentNode;
		while( !this_item.classList.contains('list-item'))
			this_item = this_item.parentNode;
	}
	if( !this_item.classList.contains('expanded') ){
		this_item.classList.add('expanded');
	}
	else
	{
		this_item.classList.remove('expanded');
	}
}

var sSection_block = document.querySelectorAll('.section-block, .section-block .block-content, .tab-content');
[].forEach.call(sSection_block, function(el, i){
	el.classList.add('js-enabled');
});

body.classList.add('jsEnabled');

/* scroll */

if(wW >= 782)
{
	console.log(wW > 782);
	var sTop = parseInt(window.scrollY);
	var ticking = false;
	var sLanding_block = document.getElementsByClassName('landing-block')[0];
	var folded_landing_block = document.createElement('DIV');
	folded_landing_block.id = 'folded-landing-block';
	var site_name_2 = document.createElement('IMG');
	site_name_2.id = 'site-name-2';
	site_name_2.src = '/wp-content/themes/voicingpoverty/media/logo_long.png';
	var portal_btn_2 = sLanding_block.querySelector('#participant-portal-btn-1').cloneNode(true);
	portal_btn_2.id = 'participant-portal-btn-2';
	folded_landing_block.appendChild(site_name_2);
	folded_landing_block.appendChild(portal_btn_2);
	var sPrimary = document.getElementById('primary');
	var folded_landing_block_bg = document.createElement('DIV');
	folded_landing_block_bg.id = 'folded-landing-block-bg';
	var folded_landing_block_container = document.createElement('DIV');
	folded_landing_block_container.id = 'folded-landing-block-container';
	folded_landing_block_container.appendChild(folded_landing_block);
	folded_landing_block_container.appendChild(folded_landing_block_bg);
	sPrimary.appendChild(folded_landing_block_container);
	var landing_block_isFolded = false;
	var sTop_bar_landing_block = sLanding_block.offsetHeight - 50;
	if(window.scrollY > sTop_bar_landing_block){
		body.classList.add('viewing-folded-landing-block');
		landing_block_isFolded = true;
	}
	function scroll(sTop){
		if(sTop > sTop_bar_landing_block && !landing_block_isFolded){
			body.classList.add('viewing-folded-landing-block');
			landing_block_isFolded = true;
		}
		else if(sTop <= sTop_bar_landing_block && landing_block_isFolded)
		{
			body.classList.remove('viewing-folded-landing-block');
			landing_block_isFolded = false;
		}
	}
	window.addEventListener('scroll', function(){
		if(!ticking) {
			sTop = parseInt(window.scrollY);
			requestAnimationFrame(function(){
				scroll(sTop);
				ticking = false;
			});				
		}
		ticking = true;
	});
	
	window.addEventListener('load', function(){
		sTop_bar_landing_block = sLanding_block.offsetHeight - 50;
	});
}

class Gallery {
	constructor( el ){
		this.slideshow_container = el;
		this.gallery_items = el.querySelectorAll('.blocks-gallery-item');
		this.next_btn = el.querySelector('.slideshow-control-next');
		this.prev_btn = el.querySelector('.slideshow-control-prev');
		
		this.idx = 0;
	}
	init(){
		var self = this;
		this.next_btn.addEventListener('click', function(){
			self.next_slide();
		}, false);
		this.prev_btn.addEventListener('click', function(){
			self.prev_slide();
		}, false);
	}
	next_slide(){
		console.log(this);
		var viewing = this.gallery_items[this.idx];
		if(viewing != null)
		{
			viewing.classList.remove('viewing');
			if(this.idx == this.gallery_items.length - 1)
				this.idx = 0;
			else
				this.idx++;
			this.gallery_items[this.idx].classList.add('viewing');
		}
	}
	prev_slide(){
		var viewing = this.gallery_items[this.idx];
		if(viewing != null)
		{
			viewing.classList.remove('viewing');
			if(this.idx == 0)
				this.idx = this.gallery_items.length - 1;
			else
				this.idx--;
			this.gallery_items[this.idx].classList.add('viewing');
		}
	}
}
var sSlideshow_container = document.getElementsByClassName('slideshow-container');
var gallery_array = [];
[].forEach.call(sSlideshow_container, function(el, i){
	el.classList.add('js-enabled');
	var firstGelleryItem = el.querySelector('.blocks-gallery-item');
	if(firstGelleryItem != null){
		firstGelleryItem.classList.add('viewing');
		var this_control = document.createElement('DIV');
		this_control.className = 'slideshow-control';
		var this_next = document.createElement('A');
		this_next.className = 'slideshow-control-next slideshow-control-part';
		this_next.innerHTML = '<span class="arrow-container"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="10.98 18 19 10 10.98 2 8.87 4.11 13.26 8.5 1 8.5 1 11.5 13.27 11.5 8.87 15.89 10.98 18"/></svg></span>';
		var this_prev = document.createElement('A');
		this_prev.innerHTML = '<span class="arrow-container"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="9.02 2 1 10 9.02 18 11.13 15.89 6.74 11.5 19 11.5 19 8.5 6.74 8.5 11.13 4.11 9.02 2"/></svg></span>';
		this_prev.className = 'slideshow-control-prev slideshow-control-part';
		this_control.appendChild(this_prev);
		this_control.appendChild(this_next);
		el.appendChild(this_control);

		var this_galley = new Gallery(el);
		this_galley.init();
		gallery_array.push(this_galley);
	}	
});


var hashLink = document.querySelectorAll('a[href^="#"]');
hashLink.forEach(anchor => {
    anchor.addEventListener('click', function (e) {
    	console.log('click');
        var target_id = anchor.getAttribute('href').substr(1); 
        if( target_id != undefined)
            var target = document.getElementById(target_id);
        else
            var target = null;
        if(target != null && target.classList.contains('foldable')){
            target.classList.add('expanded');
        }
    });
});

