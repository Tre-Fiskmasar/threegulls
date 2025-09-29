document.addEventListener("DOMContentLoaded", async () => {
    const peopleContainer = document.getElementById("people");

    const createPersonCard = (person) => {
        const card = document.createElement("div");
        card.classList.add("person-card");
        card.innerHTML = `
            <img src="/public/img/${person.image}" alt="Profile picture of ${person.Name}">
            <h2>${person.Name}</h2>
            <p>${person.Description}</p>
            <p><strong>Qualifications:</strong> ${person.qualifications}</p>
            <p><strong>Email:</strong> <a href="mailto:${person.Contacts.Email}">${person.Contacts.Email}</a></p>
            <p><strong>Phone:</strong> <a href="tel:${person.Contacts.Number}">${person.Contacts.Number}</a></p>
        `;
        return card;
    };

    try {
        const siteData = await fetch("/public/data/Site.json").then(res => res.json());
        
        document.getElementById("team-title").innerText = siteData.Title;
        document.getElementById("logo").src = `/public/img/${siteData.logo}`;
        document.getElementById("logo").alt = `${siteData.Title} Logo`;
        document.getElementById("team-selling").innerText = siteData.Sellingpoint;
        

        document.getElementById("team-description").innerText = siteData.Description;
        document.getElementById("team-finnish").innerText = siteData.Finnish;

        document.getElementById("footer").innerText = siteData.Footer;

        const peopleFiles = ["Victor.json", "Leon.json", "Jack.json", "Hampus.json"];

        for (const file of peopleFiles) {
            const person = await fetch(`/public/data/${file}`).then(res => res.json());
            const personCard = createPersonCard(person);
            peopleContainer.appendChild(personCard);
        }
    } catch (error) {
        console.error("Failed to load data:", error);
        peopleContainer.innerHTML = `<p style="text-align: center; color: red;">Sorry, we couldn't load the team data. Please try again later.</p>`;
    }
});