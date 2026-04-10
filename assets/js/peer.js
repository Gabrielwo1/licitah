import {Peer} from "https://esm.sh/peerjs@1.5.2?bundle-deps"

function montaPeer(){
     var peer = new Peer();
           
    console.log("era pra funcionar")
            peer.on('open', function(id) {
	console.log('My peer ID is: ' + id);
  });
       
       
       
       
       
     
}

  montaPeer();