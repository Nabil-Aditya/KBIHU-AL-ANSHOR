<!-- Custom Cursor Elements -->
<div class="custom-cursor"></div>
<div class="cursor-follower"></div>

@push('scripts')
<script>
console.log('🚀 CURSOR SCRIPT LOADED');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOMContentLoaded - Initializing cursor');
    
    // Elements
    const cursor = document.querySelector('.custom-cursor');
    const follower = document.querySelector('.cursor-follower');
    
    console.log('🔍 Cursor element found:', cursor);
    console.log('🔍 Follower element found:', follower);
    
    if (!cursor || !follower) {
        console.error('❌ Cursor elements not found!');
        return;
    }
    
    // Variables
    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;
    let followerX = 0;
    let followerY = 0;
    
    // Force show untuk debugging
    cursor.style.opacity = '1';
    follower.style.opacity = '0.8';
    cursor.style.background = 'red';
    follower.style.borderColor = 'green';
    
    // Add active class
    cursor.classList.add('active');
    follower.classList.add('active');
    
    console.log('🎯 Cursor activated');
    
    // Mouse move event
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
        console.log('📍 Mouse position:', mouseX, mouseY);
    });
    
    // Animation loop
    function animateCursor() {
        // Smooth movement for cursor
        cursorX += (mouseX - cursorX) * 0.1;
        cursorY += (mouseY - cursorY) * 0.1;
        
        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';
        
        // Slower movement for follower
        followerX += (mouseX - followerX) * 0.05;
        followerY += (mouseY - followerY) * 0.05;
        
        follower.style.left = followerX + 'px';
        follower.style.top = followerY + 'px';
        
        requestAnimationFrame(animateCursor);
    }
    
    // Start animation
    animateCursor();
    console.log('🔄 Animation started');
    
    // Hover effects
    const hoverElements = ['a', 'button', '.btn', 'input', 'textarea', 'select'];
    
    hoverElements.forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
            element.addEventListener('mouseenter', function() {
                console.log('🖱️ Hover on:', this.tagName, this.className);
                cursor.classList.add('hover');
                follower.classList.add('hover');
            });
            
            element.addEventListener('mouseleave', function() {
                cursor.classList.remove('hover');
                follower.classList.remove('hover');
            });
        });
    });
    
    // Click effects
    document.addEventListener('mousedown', function() {
        console.log('🖱️ Mouse down');
        cursor.classList.add('click');
        follower.classList.add('click');
    });
    
    document.addEventListener('mouseup', function() {
        cursor.classList.remove('click');
        follower.classList.remove('click');
    });
    
    console.log('✅ Cursor initialized successfully');
    
    // Export untuk debugging
    window.debugCursor = {
        cursor: cursor,
        follower: follower,
        show: function() {
            cursor.style.opacity = '1';
            follower.style.opacity = '0.8';
        },
        hide: function() {
            cursor.style.opacity = '0';
            follower.style.opacity = '0';
        }
    };
});
</script>
@endpush