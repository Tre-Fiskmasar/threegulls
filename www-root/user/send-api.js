

document.addEventListener('DOMContentLoaded', () => {
  axios.get("http://localhost:5150/api/seagulls.php", {
    headers: { "X-API-Key": apiKey }
  }) //send API key from seagulls.php with axios
    .then(res => {
      
      console.log("Status:", res.status);
      console.log("Data:", res.data); 
      //print the status + data of get request 
    })
    .catch(err => console.error("Error:", err));
    //if theres an error, catch it and print it to console
});
