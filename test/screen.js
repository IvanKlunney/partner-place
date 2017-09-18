var uri, scheme, runner, proc, i = 0;
const fs = require('fs'),
	  cp = require('child_process');
	  process.argv.forEach(function(v, i, a){
	  		switch(v) {
	  			case '-u': 
	  				uri = a[i + 1];
	  				break;
	  			case '-s': 
	  				scheme = a[i + 1];
	  				break;
	  			case '-r': 
	  				runner = a[i + 1];
	  				break;
	  			case 'default': 
	  				return;
	  		}
	  });

scheme = JSON.parse( fs.readFileSync(scheme, 'utf8') );

while(i < scheme.resolution.length) {
	proc = cp.spawn('phantomjs', [runner, JSON.stringify(scheme.resolution[i]), uri]);
	proc.on('error', function(err){
		console.log(err);
	})
	i++;
}

process.exit();


