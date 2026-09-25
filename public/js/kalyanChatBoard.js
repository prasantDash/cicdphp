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
     if (Array.isArray(data)) {
        
        data.sort((a, b) => {
            const getDateTime = item => {
                if (!item || typeof item !== 'object') return 0;
                const date = item.result_date ?? item.Date ?? '';
                const time = item.close_time ?? item.Time ?? '';
                return Date.parse(`${date} ${time}`) || 0;
            };
            return getDateTime(a) - getDateTime(b);
        });
    }
    const sortedData = data.map(item => {
        const date = new Date(item.result_date ?? item.Date ?? '');
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
        return { ...item, dayName };
    });

    const chunks = [];
    let currentChunk = [];
    sortedData.forEach(item => {
        currentChunk.push(item);
        if (String(item.dayName).toLowerCase() === 'sunday') {
            chunks.push(currentChunk);
            currentChunk = [];
        }
    });
    if (currentChunk.length > 0) chunks.push(currentChunk);
    const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday","Sunday"];
    chunks.forEach((chunk, index) => {
        chunk.unshift({
            fromdate: chunk[0].result_date ?? chunk[0].Date ?? '',
            todate: chunk[chunk.length - 1].result_date ?? chunk[chunk.length - 1].Date ?? '',
        });
        daysOfWeek.forEach(day => {
            if (!chunk.some(item => String(item.dayName).toLowerCase() === day.toLowerCase())) {
                const missingItem = {
                    name: "KALYAN MORNING",
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
    

    console.log('Fetched data chunks:', chunks);
    const table = document.getElementById("chatTableBody");
    chunks.forEach((chunk, index) => {
        const chunkContainer = document.createElement('tr');
        chunk.forEach(item => {
            console.log('Rendering item:', item);
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