class nownCanvas
{
    constructor(el, options = {})
    {
        this.el = document.querySelector('#'+el);
        this.id = this.el.id;
        
        this.options = {
            panLimit : options.panLimit || 0.6,
            closeVelocity : options.closeVelocity || 0.8,
            dirDesktop : options.dirDesktop || 'right',
            dirMobile : options.dirMobile || 'bottom',
            backdropBlur : (options.backdropBlur == null ? true : options.backdropBlur),
            backdropClose : (options.backdropClose == null ? true : options.backdropClose),
            escapeClose : (options.escapeClose == null ? true : options.escapeClose),
            desktopPan : (options.desktopPan == null ? true : options.desktopPan),
            mobilePan : (options.mobilePan == null ? true : options.mobilePan),
            persist : (options.persist == null ? false : options.persist)
        };
        
        this.triggers.bind(this)();
    }
    
    show()
    {
  
        var width = document.body.clientWidth;
        if(width <= 991){
            this.direction = this.options.dirMobile;
            this.initHammer = this.options.mobilePan;
        } else {
            this.direction = this.options.dirDesktop;
            this.initHammer = this.options.desktopPan;
        }
        this.open.bind(this)();
    }
    
    hide()
    {
        this.close.bind(this)();
    }
    
    on(e, cb)
    {
        switch(e)
        {
            case 'show':
                this.onShowEvent = cb;
            break;
            case 'shown':
                this.onShownEvent = cb;
            break;
            case 'hidden':
                this.onHiddenEvent = cb;
            break;
            case 'hide':
                this.onHideEvent = cb;
            break;
        }
    }
    
    open(){
        if(!this.backdrop){
            if(this.onShowEvent){
                this.onShowEvent(this);
            }
            
            this.createBackdrop.bind(this)();
            
            this.el.className = `nown-canvas ${this.direction}`;
            this.backdrop.style.display = 'block';
            this.el.style.display = 'block';
            this.el.style.transition = `${this.direction} .27s ease`;
            
            this.el.style.removeProperty('left')
            this.el.style.removeProperty('bottom')
            this.el.style.removeProperty('right')
            this.el.style.removeProperty('top')
            this.el.style.removeProperty('height')
            
            switch(this.direction)
            {
                case 'bottom':
                    this.el.style.bottom = this.el.getBoundingClientRect().height * -1;
                    this.el.style.height = this.el.getBoundingClientRect().height;
                    setTimeout(()=>{
                        this.backdrop.style.opacity = 1;
                        this.el.style.bottom = 0;
                    }, 50)
                break;
                case 'top':
                    this.el.style.top = this.el.getBoundingClientRect().height * -1;
                    this.el.style.height = this.el.getBoundingClientRect().height;
                    setTimeout(()=>{
                        this.backdrop.style.opacity = 1;
                        this.el.style.top = 0;
                    }, 50)
                break;
                case 'left':
                    this.el.style.left = this.el.getBoundingClientRect().width * -1;
                    setTimeout(()=>{
                        this.backdrop.style.opacity = 1;
                        this.el.style.left = 0;
                    }, 50)
                break;
                case 'right':
                    this.el.style.right = this.el.getBoundingClientRect().width * -1;
                    setTimeout(()=>{
                        this.backdrop.style.opacity = 1;
                        this.el.style.right = 0;
                    }, 50)
                break;
            }
            
            this.hammers.bind(this)();
            
            setTimeout(()=>{
                if(this.onShownEvent){
                    this.onShownEvent(this);
                }
            }, 270);
        
        }
    }
    
    close(){
        if(this.onHideEvent){
            this.onHideEvent(this);
        }
        
        switch(this.direction)
        {
            case 'bottom':
                this.el.style.bottom = this.el.getBoundingClientRect().height * -1;
            break;
            case 'top':
                this.el.style.top = this.el.getBoundingClientRect().height * -1;
            break;
            case 'left':
                this.el.style.left = this.el.getBoundingClientRect().width * -1;
            break;
            case 'right':
                this.el.style.right = this.el.getBoundingClientRect().width * -1;
            break;
        }
        this.backdrop.style.opacity = 0;
        setTimeout(()=>{
            if(this.backdrop){
                 this.backdrop.style.display = 'none';
                 this.backdrop.remove();
                 this.backdrop = false;
            }
            
       
            this.el.style.display = 'none';
            this.el.className = `nown-canvas`;
        
            if(this.onHiddenEvent){
                this.onHiddenEvent(this);
            }
            if(this.initHammer){
                this.hammer.destroy();
            }
        }, 270)
    }
    
    hammers(){
        if(this.initHammer){
            var target = this.el;
            var backdrop = this.backdrop;
            var options = this.options;
            var direction = this.direction;
            
            const close = () => {
                this.close.bind(this)();
            }
            
            switch(direction)
            {
                case 'bottom':
                    this.hammer = new Hammer(target, {});
                    this.hammer.on('pan', function(ev) {
                        target.style.transition = 'none';
            	        target.style.bottom = (ev.deltaY >= 0 ? ev.deltaY : 0) * -1;
            	        if(ev.deltaY >= 0){
            	            backdrop.style.opacity = ((Math.round((ev.deltaY / target.getBoundingClientRect().height) * 100) / 100) - 1) * -1;
            	        }
                    });
                    this.hammer.on('panend', function(ev) {
                        target.style.transition = 'bottom .27s ease';
                	    if(ev.deltaY > target.getBoundingClientRect().height * options.panLimit || ev.velocityY > options.closeVelocity){
                	        close();
                	    } else {
                	        target.style.bottom = 0;
            	            backdrop.style.opacity = 1;
                	    }
                    });
                    this.hammer.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });
                break;
                case 'top':
                    this.hammer = new Hammer(target, {});
                    this.hammer.on('pan', function(ev) {
                        target.style.transition = 'none';
            	        target.style.top = (ev.deltaY <= 0 ? ev.deltaY : 0);
            	        if(ev.deltaY * -1 >= 0){
            	            backdrop.style.opacity = ((Math.round(((ev.deltaY * -1) / target.getBoundingClientRect().height) * 100) / 100) - 1) * -1;
            	        }
                    });
                    this.hammer.on('panend', function(ev) {
                        target.style.transition = 'top .27s ease';
                	    if(ev.deltaY > target.getBoundingClientRect().height * options.panLimit || ev.velocityY < options.closeVelocity * -1){
                	        close();
                	    } else {
                	        target.style.top = 0;
            	            backdrop.style.opacity = 1;
                	    }
                    });
                    this.hammer.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });
                break;
                case 'left':
                    this.hammer = new Hammer(target, {});
                    this.hammer.on('pan', (ev)=> {
                        if(ev.eventType == 2 || ev.eventType == 4){
                            target.style.transition = 'none';
                	        target.style.left = (ev.deltaX <= 0 ? ev.deltaX : 0);
                	        if(ev.deltaX <= 0){
                	            backdrop.style.opacity = ((Math.round(((ev.deltaX * -1) / target.getBoundingClientRect().width) * 100) / 100) - 1) * -1;
            	            }
            	            var divs = target.querySelectorAll('*');
            	            divs.forEach(div => {
            	                div.style.pointerEvents = 'none';
            	                
            	                if(this.options.persist){
            	                     div.addEventListener('scroll',(e) => {
            	                    target.style.transition = 'left .27s ease';
            	                    target.style.left = 0;
                    	            backdrop.style.opacity = 1;
                	                divs.forEach(div2 => { div2.style.removeProperty('pointer-events');});
            	                })
            	                }else{
            	                   evento(div,'scroll',(e) => {
            	                    target.style.transition = 'left .27s ease';
            	                    target.style.left = 0;
                    	            backdrop.style.opacity = 1;
                	                divs.forEach(div2 => { div2.style.removeProperty('pointer-events');});
            	                })  
            	                }
            	               
            	            })
            	        }
                    });
                    this.hammer.on('panend', function(ev) {
                
                        target.style.transition = 'left .27s ease';
                	    if(ev.deltaX * -1 > target.getBoundingClientRect().width * options.panLimit || ev.velocityX < options.closeVelocity * -1){
                	        close();
                	    } else {
                	        target.style.left = 0;
            	            backdrop.style.opacity = 1;
                	    }
        	            var divs = target.querySelectorAll('*');
        	            divs.forEach(div => {
        	                div.style.removeProperty('pointer-events');
        	            })
                    });
                    this.hammer.get('pan').set({ direction: Hammer.DIRECTION_ALL });
                break;
                case 'right':
                    this.hammer = new Hammer(target, {});
                    this.hammer.on('pan', function(ev) {
                        if(ev.eventType == 2 || ev.eventType == 4){
                            target.style.transition = 'none';
                	        target.style.right = (ev.deltaX >= 0 ? ev.deltaX * -1 : 0);
                	        if(ev.deltaX >= 0){
                	            backdrop.style.opacity = ((Math.round((ev.deltaX / target.getBoundingClientRect().width) * 100) / 100) - 1) * -1;
                	        }
            	            var divs = target.querySelectorAll('*');
            	            divs.forEach(div => {
            	                div.style.pointerEvents = 'none';
            	                div.addEventListener('scroll',(e) => {
            	                    target.style.transition = 'right .27s ease';
            	                    target.style.right = 0;
                    	            backdrop.style.opacity = 1;
                	                divs.forEach(div2 => { div2.style.removeProperty('pointer-events');});
            	                })
            	            })
                        }
                    });
                    this.hammer.on('panend', function(ev) {
                        target.style.transition = 'right .27s ease';
                	    if(ev.deltaX > target.getBoundingClientRect().width * options.panLimit || ev.velocityX > options.closeVelocity){
                	        close();
                	    } else {
                	        target.style.right = 0;
            	            backdrop.style.opacity = 1;
                	    }
        	            var divs = target.querySelectorAll('*');
        	            divs.forEach(div => {
        	                div.style.removeProperty('pointer-events');
        	            })
                    });
                    this.hammer.get('pan').set({ direction: Hammer.DIRECTION_HORIZONTAL });
                break;
            }
        }
    }
    
    triggers()
    {
        const triggers = document.querySelectorAll(`[data-nown-canvas="${this.id}"]`);
        if(triggers){
            triggers.forEach(trigger => {
                if(this.options.persist){
                    trigger.addEventListener('click', () => {
                    this.show.bind(this)();
                })
                }else{
                    evento(trigger, 'click', () => {
                    this.show.bind(this)();
                })
                }
                
            });
        }
        
        const closeTriggers = this.el.querySelectorAll('[data-close-canvas');
        if(closeTriggers){
            closeTriggers.forEach(trigger => {
                if(this.options.persist){
                     trigger.addEventListener('click', () => {
                    this.close.bind(this)();
                })
                }else{
                    evento(trigger, 'click', () => {
                    this.close.bind(this)();
                }) 
                }
               
            });
        }
        
        if(this.options.escapeClose === true) {
            if(this.options.persist){
                  window.addEventListener('keydown', () => {
                if (event.keyCode && event.keyCode == 27) {
                    if(this.backdrop){
                        this.close.bind(this)();
                    }
                }
            });
            }else{
                   evento(window, 'keydown', () => {
                if (event.keyCode && event.keyCode == 27) {
                    if(this.backdrop){
                        this.close.bind(this)();
                    }
                }
            });
            }
         
        }
    }
    
    createBackdrop()
    {
        var backdrops = document.querySelectorAll('.nown-canvas-backdrop');
        if(backdrops){
            backdrops.forEach(backdrop => { backdrop.click(); backdrop.remove() })
        }
        
        this.backdrop = document.createElement('DIV');
        this.backdrop.classList.add('nown-canvas-backdrop');
        if(!this.options.backdropBlur){
            this.backdrop.classList.add('no-blur')
        }
        document.body.append(this.backdrop);
        
        if(this.options.backdropClose === true) {
             if(this.options.persist){
                  this.backdrop.addEventListener('click', () => {
                    this.close.bind(this)();
            });
             }else{
                  evento(this.backdrop, 'click', () => {
                    this.close.bind(this)();
            });
             }
           
        }
    }
}