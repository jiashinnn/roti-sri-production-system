// Switch Tabs Functionality
function switchTab(tabId) {
    // Hide all tab content
    const allTabs = document.querySelectorAll('.tab-content');
    allTabs.forEach(tab => tab.classList.add('hidden'));
    
    // Remove active class from all tabs
    const allLinks = document.querySelectorAll('nav ul li a');
    allLinks.forEach(link => link.classList.remove('active'));

    // Show the selected tab
    document.getElementById(tabId).classList.remove('hidden');
    document.getElementById(tabId).classList.add('active');

    // Add active class to the clicked tab
    document.querySelector(`#${tabId}-tab`).classList.add('active');
}
