<style>
    .optimized-component > div {
  display: flex;
  justify-content: flex-start;
  gap: 12px;
  align-items: center;
  padding: 10px;
  text-align: left;
  
  
}

.optimized-component .avatar-container {
  width: 58px;
  height: 58px;
  position: relative;
}

.optimized-component .avatar {
  width: 100%;
  height: 100%;
  background-color: #dc3545;
  border-radius: 50%;
}

.optimized-component .status {
  width: 25%;
  height: 25%;
  background-color: #ffc107;
  border-radius: 50%;
  position: absolute;
  bottom: 0;
  right: 0;
}

.optimized-component .text-content h4 {
  font-size: 16px;
  font-weight: 700;
  margin: 0;
}

.optimized-component .text-content p {
  font-size: 12px;
  margin: 0;
}



body.light .optimized-component.unread {
  background-color: #f0f4f9;
}

body.light .optimized-component:hover {
  background-color: rgb(248, 249, 250, 0.4);
}



body.dark .optimized-component.unread{
  background-color: #212529; 
}

body.dark .optimized-component:hover {
  background-color: rgb(33, 37, 41, 0.4);
}
 
</style>
<div class="nown-canvas" id="ocNotif">
    <div class="nown-canvas-content">
        <header>
            <h3>Notificações</h3>
            <button class="btn-close-nown-canvas" data-close-canvas></button>
        </header>
        <main>
            <div class="d-flex flex-column gap-2 lista">
          
            </div>
        </main>
    </div>
    <div class="controller"></div>
</div>