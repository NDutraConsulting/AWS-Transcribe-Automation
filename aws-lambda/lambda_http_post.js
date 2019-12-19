const http = require('http');

exports.handler = async (event, context) => {
  //Does not like the pure ip address
  let url = "ec2-18-220-105-175.us-east-2.compute.amazonaws.com";
  let path = "/test_reciever.php";//?"+json;//'/lambda_start_transcribe.php'; //


  let json = "json="+JSON.stringify(event);
  let postData = json;

    return new Promise((resolve, reject) => {
        const options = {
            host: url,
            path: path,
            port: 80,
            method: 'POST',
              headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    'Content-Length': Buffer.byteLength(postData)
  }
        };

        //console.log(options);

        const req = http.request(options, (res) => {
          resolve('Success');
        });

        req.on('error', (e) => {
          reject(e.message);
        });

        //console.log(postData);
        // send the request
        req.write(postData);
        req.end();
    });
};
