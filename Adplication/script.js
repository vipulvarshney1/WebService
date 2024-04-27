// // script.js
// document.addEventListener("DOMContentLoaded", function() {
//     const submitBtn = document.getElementById('submitBtn');
//     submitBtn.addEventListener('click', function() {
//         const selectedCategory = document.getElementById('category').value;
        
//         fetch('Categoy_Benchmarks_Jan_2024.json') // Replace 'data.json' with the path to your JSON file
//         .then(response => response.json())
//         .then(data => {
//             const dashboardContainer = document.querySelector('.dashboard-container');
//             dashboardContainer.innerHTML = ''; // Clear previous dashboard elements

//             const categoryData = data[selectedCategory];
            
//             // Loop through each item in the selected category
//             categoryData.forEach(item => {.
//                 const dashboardElement = document.createElement('div');
//                 dashboardElement.classList.add('dashboard-element');
//                 dashboardElement.innerHTML = `
//                     <h2>${item["Subcategory Name"]}</h2>
//                     <p>CPC: ${item.CPC}</p>
//                     <p>CTR%: ${item["CTR%"]}</p>
//                     <p>CVR%: ${item["CVR%"]}</p>
//                     <p>ACoS: ${item.ACoS}</p>
//                     <p>ROAS: ${item.ROAS}</p>
//                 `;
//                 dashboardContainer.appendChild(dashboardElement);
//             });
//         })
//         .catch(error => console.error('Error fetching data:', error));
//     });
// });
