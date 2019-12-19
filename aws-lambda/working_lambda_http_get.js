
const http = require('http');

exports.handler = async function(event) {
let url = "http://18.220.105.175/job_complete.php?json="+JSON.stringify(event);


  const promise = new Promise(function(resolve, reject) {
    http.get(url, (res) => {

        resolve(res.statusCode);

        console.log("LOG:"+JSON.stringify(event));
        
      }).on('error', (e) => {
        reject(Error(e))
      })
    })
  return promise;
}
