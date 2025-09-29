

document.addEventListener('DOMContentLoaded', () => {
  axios.get("http://localhost:5150/api/seagulls.php", {
    headers: { "X-API-Key": apiKey }
  })
    .then(res => {
      
      console.log("Status:", res.status);
      console.log("Data:", res.data);
    })
    .catch(err => console.error("Error:", err));
});
