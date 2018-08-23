$(document).ready(function(){
	$('.attractive-recruitment').owlCarousel({
		loop:true,
		margin: 10,
		autoplay:false,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$('.recruitment-related').owlCarousel({
		loop:true,
		margin: 10,
		autoplay:false,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$('.main-recruitment').owlCarousel({
		loop:true,
		margin: 10,
		autoplay:false,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	})
	$('.main-category-article').owlCarousel({
		loop:true,
		margin: 10,
		autoplay:false,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$('.high-salary-recruitment').owlCarousel({
		loop: true,
		margin: 10,
		autoplay:true,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$('.interested-recruitment').owlCarousel({
		loop: true,
		margin: 10,
		autoplay:true,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$('.employer-top-box .owl-carousel').owlCarousel({
		loop: true,
		margin: 10,
		autoplay:true,
		responsiveClass: true,
		smartSpeed: 1200,
		navText : ['<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>','<i class="fas fa-arrow-circle-right" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 1,
				nav: true
			},
			1000: {
				items: 1,
				nav: true,
				loop: true,
				margin: 20
			}
		}
	});
	$(".banner").owlCarousel({
      autoplay:true,                    
      loop:true,
      margin:0,                        
      nav:false,            
      mouseDrag: true,
      touchDrag: true,                                
      responsiveClass:true,
      responsive:{
        0:{
          items:1
        },
        600:{
          items:1
        },
        1000:{
          items:1
        }
      }
    });
});