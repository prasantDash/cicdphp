const chatData = document.getElementById('chatData');

function renderValue(value) {
if (Array.isArray(value)) {
    const list = document.createElement('ul');
    value.forEach(item => {
        const entry = document.createElement('li');
        entry.appendChild(renderValue(item));
        list.appendChild(entry);
    });
    return list;
}

if (value && typeof value === 'object') {
    const table = document.createElement('table');
    Object.entries(value).forEach(([key, item]) => {
        const row = table.insertRow();
        const heading = row.insertCell();
        const content = row.insertCell();
        heading.textContent = key;
        content.appendChild(renderValue(item));
    });
    return table;
}

const text = document.createElement('span');
text.textContent = value ?? '';
return text;
}

fetch('./kalyanData.json')
.then(response => {
    if (!response.ok) throw new Error(`Request failed: ${response.status}`);
    return response.json();
})
.then(data => {
    //console.log('Fetched data:', data, 'items');
    
    const sortedData = data.map(item => {
        const dateValue = item.result_date ?? item.Date ?? '';
        // JavaScript does not reliably parse dates in DD/MM/YYYY format.
        const match = String(dateValue).match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        const date = match
            ? new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]))
            : new Date(dateValue);
        const dayName = Number.isNaN(date.getTime())
            ? ''
            : date.toLocaleDateString('en-US', { weekday: 'long' });
        return { item, timestamp: date.getTime(), dayName };
    }).sort((a, b) => {
        const aInvalid = Number.isNaN(a.timestamp);
        const bInvalid = Number.isNaN(b.timestamp);
        if (aInvalid || bInvalid) return aInvalid === bInvalid ? 0 : aInvalid ? 1 : -1;
        return a.timestamp - b.timestamp;
    }).map(({ item, dayName }) => ({ ...item, dayName }));


    const chunks = [];
    let currentChunk = [];
    sortedData.forEach(item => {
        currentChunk.push(item);
        if (String(item.dayName).toLowerCase() === 'saturday') {
            chunks.push(currentChunk);
            currentChunk = [];
        }
    });
    if (currentChunk.length > 0) chunks.push(currentChunk);
    const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    chunks.forEach((chunk, index) => {
        chunk.unshift({
            fromdate: chunk[0].result_date ?? chunk[0].Date ?? '',
            todate: chunk[chunk.length - 1].result_date ?? chunk[chunk.length - 1].Date ?? '',
        });
        daysOfWeek.forEach(day => {
            if (!chunk.some(item => String(item.dayName).toLowerCase() === day.toLowerCase())) {
                const missingItem = {
                    name: "KALYAN",
                    open: "***",
                    jodi: "**",
                    close: "***",
                    result: "***-**-***",
                    open_time: "**",
                    close_time: "**",
                    result_date: "**",
                    fetch_datetime: null,
                    dayName: day
                };

                const dayIndex = daysOfWeek.indexOf(day);
                const insertIndex = chunk.findIndex(item => {
                    const itemDayIndex = daysOfWeek.indexOf(item.dayName);
                    return itemDayIndex !== -1 && itemDayIndex > dayIndex;
                });
                chunk.splice(
                    insertIndex === -1 ? chunk.length : insertIndex,
                    0,
                    missingItem
                );
            }
        });
        
    });
    

    
    const table = document.getElementById("chatTableBody");
    chunks.forEach((chunk, index) => {
        const chunkContainer = document.createElement('tr');
        chunk.forEach(item => {
            //console.log('Rendering item:', item);
            const cell = document.createElement('td');
            if(item.fromdate && item.todate) {
                cell.innerHTML = `${item.fromdate} <br><br> ${item.todate}`;
            }else{
                cell.innerHTML = `<div class="item-container"><div class="open-class">${item.open}</div><div style="display: flex; align-items: center;"><div class="red-circle-badge" title="${item.dayName} and ${item.result_date}"><b> ${item.jodi} </b></div></div> <div class="open-class">${item.close}</div></div>`;
            }
            
            chunkContainer.appendChild(cell);
        });
        table.appendChild(chunkContainer);
    });
})
.catch(error => {
    chatData.textContent = `Unable to load chat data: ${error.message}`;
});

function chunkArray(arr, size) {
    const chunked = [];
    for (let i = 0; i < arr.length; i += size) {
        chunked.push(arr.slice(i, i + size));
    }
    return chunked;
}
//Search button functionality
const searchButton = document.getElementById('searchButton');
searchButton.addEventListener('click', () => {
    const fromDateInput = document.getElementById('search-chat-Form');
    const formData = new FormData(fromDateInput);
    const formDataObject = Object.fromEntries(formData.entries());
    
    const isEmpty = Object.values(formDataObject).every(value => value.trim() === "");

    if (isEmpty) {
        alert("All properties are empty!");
        return;
        // Your logic here (e.g., show an alert, stop form submission)
    }
    fetch('./kalyanData.json')
    .then(response => {
        if (!response.ok) throw new Error(`Request failed: ${response.status}`);
        return response.json();
    })
    .then(data => {
        console.log('Fetched data:', data);
        
        const sortedData = data.map(item => {
            const dateValue = item.result_date ?? item.Date ?? '';
            // JavaScript does not reliably parse dates in DD/MM/YYYY format.
            const match = String(dateValue).match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
            const date = match
                ? new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]))
                : new Date(dateValue);
            const dayName = Number.isNaN(date.getTime())
                ? ''
                : date.toLocaleDateString('en-US', { weekday: 'long' });
            return { item, timestamp: date.getTime(), dayName };
        }).sort((a, b) => {
            const aInvalid = Number.isNaN(a.timestamp);
            const bInvalid = Number.isNaN(b.timestamp);
            if (aInvalid || bInvalid) return aInvalid === bInvalid ? 0 : aInvalid ? 1 : -1;
            return a.timestamp - b.timestamp;
        }).map(({ item, dayName }) => ({ ...item, dayName }));

        
        const chunks = [];
        let currentChunk = [];
        sortedData.forEach(item => {
            currentChunk.push(item);
            if (String(item.dayName).toLowerCase() === 'saturday') {
                chunks.push(currentChunk);
                currentChunk = [];
            }
        });
        if (currentChunk.length > 0) chunks.push(currentChunk);
        //const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        // 1. Define the days that actually have data available
        const availableDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        chunks.forEach((chunk, index) => {
            chunk.unshift({
                fromdate: chunk[0].result_date ?? chunk[0].Date ?? '',
                todate: chunk[chunk.length - 1].result_date ?? chunk[chunk.length - 1].Date ?? '',
            });
            availableDays.forEach(day => {
                if (!chunk.some(item => String(item.dayName).toLowerCase() === day.toLowerCase())) {
                    const missingItem = {
                        name: "KALYAN",
                        open: "***",
                        jodi: "**",
                        close: "***",
                        result: "***-**-***",
                        open_time: "**",
                        close_time: "**",
                        result_date: "**",
                        fetch_datetime: null,
                        dayName: day
                    };

                    const dayIndex = availableDays.indexOf(day);
                    const insertIndex = chunk.findIndex(item => {
                        const itemDayIndex = availableDays.indexOf(item.dayName);
                        return itemDayIndex !== -1 && itemDayIndex > dayIndex;
                    });
                    chunk.splice(
                        insertIndex === -1 ? chunk.length : insertIndex,
                        0,
                        missingItem
                    );
                }
            });
            
        });
        console.log('Fetched data:', chunks, 'items');
        console.log('Form data:', formDataObject);
        // Loop through the object to get each property and value
        let targetMetrics = {};

        Object.entries(formDataObject).forEach(([property, value]) => {
            //console.log(`Property: ${property} | Value: ${value}`);
            
            // 1. Split "Friday-open" into day ("Friday") and metric ("open")
            const [day, metric] = property.split('-');
            
            // 2. If testObj doesn't have this day property yet, create it as an empty object
            if (!targetMetrics[day]) {
                targetMetrics[day] = {};
            }
            
            // 3. Assign the value to the specific metric (only if you want to store it)
            targetMetrics[day][metric] = Number(value);
        });
        //Function to search days
        const searchedDays = Object.keys(targetMetrics).filter(day => {
            const config = targetMetrics[day];
            return (config.open !== 0 && config.open !== "") || 
                (config.jodi !== 0 && config.jodi !== "") || 
                (config.close !== 0 && config.close !== "");
        });
        //Conditional check
        let searchFlag = false;
        let dayToBeTaken = 11;
        let dataCount = 1;

        let searchData = [];
        let dynamicArray = [];
        chunks.forEach((chunk) => {
            if(searchFlag === false){
                chunk.forEach(item => {
                    // 2. Track if ALL searched days match their targets within this chunk
                    let allDaysMatched = false;

                    if (searchedDays.length > 0) {
                        allDaysMatched = searchedDays.every(dayName => {
                            // Find the specific day's item inside the current weekly chunk
                            const dayItem = chunk.find(item => item.dayName === dayName);
                            
                            // If the day data doesn't exist in the chunk, this day fails the match
                            if (!dayItem) return false;
                            
                            const dayConfig = targetMetrics[dayName];
                            const checks = [];
                            
                            if (dayConfig.open !== 0 && dayConfig.open !== "") {
                                checks.push(Number(dayItem.open) === Number(dayConfig.open));
                            }
                            if (dayConfig.jodi !== 0 && dayConfig.jodi !== "") {
                                checks.push(Number(dayItem.jodi) === Number(dayConfig.jodi));
                            }
                            if (dayConfig.close !== 0 && dayConfig.close !== "") {
                                checks.push(Number(dayItem.close) === Number(dayConfig.close));
                            }
                            
                            // This specific day matches only if all entered fields match perfectly
                            return checks.length > 0 && checks.every(result => result === true);
                        });
                    }

                    // 3. If every single searched day was found and matched perfectly in this week chunk
                    if (allDaysMatched) {
                        searchFlag = true;
                        dataCount = 1;
                        
                        // Pro-Tip: This is where you write your code to push the matching 'chunk' 
                        // to your visible array so the table shows the row with Monday 51 and Tuesday 29!
                    }
                                         
                    // // 1. Check if the current day configuration exists
                    // const dayConfig = targetMetrics[item.dayName];
                    // let isMatch = false;
                    // if (dayConfig && availableDays.includes(item.dayName)) {
                    //     // 2. Identify which metrics are actually provided in the targets (not 0, null, or empty string)
                    //     const checks = [];
                        
                    //     if (dayConfig.open !== 0 && dayConfig.open !== "")   checks.push(Number(item.open)  === Number(dayConfig.open));
                    //     if (dayConfig.jodi !== 0 && dayConfig.jodi !== "")   checks.push(Number(item.jodi)  === Number(dayConfig.jodi));
                    //     if (dayConfig.close !== 0 && dayConfig.close !== "") checks.push(Number(item.close) === Number(dayConfig.close));
                        
                    //     // 3. It's a match only if there's at least one active metric AND all active metrics match perfectly
                    //     if (checks.length > 0 && checks.every(result => result === true)) {
                    //         isMatch = true;
                    //     }
                    // }
                    // if (isMatch) {
                    //     searchFlag = true;
                    //     dataCount = 1;
                    // }
                });
            }
            
            if(searchFlag){
                searchData.push(chunk);
                //dynamicArray.push(chunk);                                
            }
            if(dataCount == dayToBeTaken){
                //searchData.push(dynamicArray);
                searchFlag = false;
                dynamicArray = [];                
            }
            dataCount++
        });
        //console.log(searchData," Search data");
        const searchResults = chunkArray(searchData, dayToBeTaken);
        console.log(searchResults, "final result");
        const parentSearchContainer = document.getElementById('search-result');
        parentSearchContainer.innerHTML = '';
        searchResults.forEach((searchResult)=>{            
            const createTable = document.createElement("table");
            // Adds a solid 1px gray border to the table
            createTable.style.border = "2px solid #f40b0b"; 
            // Collapses cell borders so they don't look doubled
            createTable.style.borderCollapse = "collapse";
            const createTableHead = document.createElement("thead");
            const createTableHeadTr = document.createElement("tr");
            const createTableHeaderTrCellDate = document.createElement("th");
            createTableHeaderTrCellDate.textContent = "Date";
            createTableHeaderTrCellDate.classList.add("table-header");
            const createTableHeaderTrCellMonday = document.createElement("th");
            createTableHeaderTrCellMonday.textContent = "Monday";
            createTableHeaderTrCellMonday.classList.add("table-header");
            const createTableHeaderTrCellTuesday = document.createElement("th");
            createTableHeaderTrCellTuesday.textContent = "Tuesday";
            createTableHeaderTrCellTuesday.classList.add("table-header");
            const createTableHeaderTrCellWednesday = document.createElement("th");
            createTableHeaderTrCellWednesday.textContent = "Wednesday";
            createTableHeaderTrCellWednesday.classList.add("table-header");
            const createTableHeaderTrCellThursday = document.createElement("th");
            createTableHeaderTrCellThursday.textContent = "Thursday";
            createTableHeaderTrCellThursday.classList.add("table-header");
            const createTableHeaderTrCellFriday = document.createElement("th");
            createTableHeaderTrCellFriday.textContent = "Friday";
            createTableHeaderTrCellFriday.classList.add("table-header");
            const createTableHeaderTrCellSaterday = document.createElement("th");
            createTableHeaderTrCellSaterday.textContent = "Saturday";
            createTableHeaderTrCellSaterday.classList.add("table-header");
            //Create table body tbody
            const createTableBody = document.createElement("tbody");

            searchResult.forEach((result) => {
                const createTableRow = document.createElement("tr");
                result.forEach((item) => {
                    const cell = document.createElement('td');
                    if(item.fromdate && item.todate) {
                        cell.innerHTML = `${item.fromdate} <br><br> ${item.todate}`;
                    }else{
                        cell.innerHTML = `<div class="item-container"><div class="open-class">${item.open}</div><div style="display: flex; align-items: center;"><div class="red-circle-badge" title="${item.dayName} and ${item.result_date}"><b> ${item.jodi} </b></div></div> <div class="open-class">${item.close}</div></div>`;
                    }
                    
                    createTableRow.appendChild(cell);
                });

                createTableBody.appendChild(createTableRow);
            });
            createTableHeadTr.appendChild(createTableHeaderTrCellDate);
            createTableHeadTr.appendChild(createTableHeaderTrCellMonday);
            createTableHeadTr.appendChild(createTableHeaderTrCellTuesday);
            createTableHeadTr.appendChild(createTableHeaderTrCellWednesday);
            createTableHeadTr.appendChild(createTableHeaderTrCellThursday);
            createTableHeadTr.appendChild(createTableHeaderTrCellFriday);
            createTableHeadTr.appendChild(createTableHeaderTrCellSaterday);
            
            
            createTableHead.appendChild(createTableHeadTr);
            createTable.appendChild(createTableHead);
            createTable.appendChild(createTableBody);
            parentSearchContainer.appendChild(createTable);
            
        });
        const brTag = document.createElement("br");
        parentSearchContainer.appendChild(brTag)

        
        

        
        // const table = document.getElementById("chatTableBody");
        // chunks.forEach((chunk, index) => {
        //     const chunkContainer = document.createElement('tr');
        //     chunk.forEach(item => {
        //         //console.log('Rendering item:', item);
        //         const cell = document.createElement('td');
        //         if(item.fromdate && item.todate) {
        //             cell.innerHTML = `${item.fromdate} <br><br> ${item.todate}`;
        //         }else{
        //             cell.innerHTML = `<div class="item-container"><div class="open-class">${item.open}</div><div style="display: flex; align-items: center;"><div class="red-circle-badge" title="${item.dayName} and ${item.result_date}"><b> ${item.jodi} </b></div></div> <div class="open-class">${item.close}</div></div>`;
        //         }
                
        //         chunkContainer.appendChild(cell);
        //     });
        //     table.appendChild(chunkContainer);
        // });
    })
    .catch(error => {
        chatData.textContent = `Unable to load chat data: ${error.message}`;
    });
});