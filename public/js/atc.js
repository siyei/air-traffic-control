const btnToggle = document.querySelector('.btn-boot'), 
	btnQueue 	= document.querySelector('.btn-queue'),
    stIndicator = document.querySelector('.current-status'),
    tableData   = document.querySelector('table.data tbody');

fetch('./api/status', {
    method: 'GET',
    headers:{
        'Content-Type'     : 'application/json',
        "X-Requested-With" : "XMLHttpRequest"
    }
}).then(res => res.json())
.catch(error => {})
.then((response) => {
    if( response.success ) {
        btnToggle.disabled      = false;
        btnQueue.disabled       = false;
        btnToggle.textContent   = response.data.buttonText;
        stIndicator.textContent = response.message;
    }
});

btnToggle.addEventListener('click', (e) => {
	let $this = e.currentTarget;
	if( $this.disabled ) {
		return false;
	}
	$this.disabled = true;

	fetch('./api/boot', {
        method: 'POST',
        headers:{
            'Content-Type'     : 'application/json',
            "X-Requested-With" : "XMLHttpRequest"
        }
    }).then(res => res.json())
    .catch(error => {
    	$this.disabled = false;
    	alert('An error occurred while processing the request!');
    })
    .then((response) => {
    	$this.disabled = false;
        if( !response.success ) {
        	alert(response.message);
        	return;
        }
        $this.textContent       = response.data.buttonText;
        stIndicator.textContent = response.message;
    });
});

btnQueue.addEventListener('click', (e) => {
    let $this = e.currentTarget;
    if( $this.disabled ) {
        return false;
    }
    $this.disabled = true;

    let data = {
        'type' : document.querySelector('#type').value,
        'size' : document.querySelector('#size').value
    };

    fetch('./api/queue', {
        method: 'POST',
        body : JSON.stringify(data),
        headers:{
            'Content-Type'     : 'application/json',
            "X-Requested-With" : "XMLHttpRequest"
        }
    }).then(res => res.json())
    .catch(error => {
        $this.disabled = false;
        alert('An error occurred while processing the request!');
    })
    .then((response) => {
        $this.disabled = false;
        if( !response.success ) {
            alert(response.message);
            return;
        }
        getData();
    });
});


const getData = () => {
    fetch('./api/queue', {
        method: 'GET',
        headers:{
            'Content-Type'     : 'application/json',
            "X-Requested-With" : "XMLHttpRequest"
        }
    }).then(res => res.json())
    .catch(error => {})
    .then((response) => {
        tableData.innerHTML = '';
        if( response.success ) {
            response.data.forEach((row) => {
                tableData.innerHTML += `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.priority}</td>
                        <td>${row.size}</td>
                        <td>${row.type}</td>
                        <td><button onclick="dequeue(${row.id}, this)">Remove</button></td>
                    </tr>
                `;
            });
        }
    });
};
getData();

function dequeue(id, btn){
    if( btn.disabled ) {
        return ;
    }
    btn.disabled = true;

    fetch('./api/queue/' + id, {
        method: 'DELETE',
        headers:{
            'Content-Type'     : 'application/json',
            "X-Requested-With" : "XMLHttpRequest"
        }
    }).then(res => res.json())
    .catch(error => {
        btn.disabled = false;
        alert('An error occurred while processing the request!');
    })
    .then((response) => {
        if( !response.success ) {
            btn.disabled = false;
            alert(response.message);
            return;
        }
        getData();
    });
}