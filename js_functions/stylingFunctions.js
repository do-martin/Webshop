function changeFilterSidebar() {
  let sidebar = document.querySelector('.sidebar');
  let sidebarButton = document.getElementById('sidebar-btn');
  if (sidebar) {
    let sidebarLeft = window.getComputedStyle(sidebar).getPropertyValue('left');
    if (sidebarLeft === '0px') {
      sidebar.style.left = '-250px';
      sidebarButton.innerHTML = 'Show Filters';
      document.querySelector('.content').style.marginLeft = '0';
    } else {
      sidebar.style.left = '0';
      sidebarButton.innerHTML = 'Hide Filters';
      document.querySelector('.content').style.marginLeft = '250px';
    }
  }
}

function openNav() {
  document.getElementById("mySidebar").style.width = "250px";
  document.getElementById("main-content").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main-content").style.marginLeft = "0";
}

function extractNumberFromString(string) {
  return string.match(/\d+/g).map(Number);
}

function sortProducts(sortType) {
  const list = document.querySelector('#all-products');
  if (sortType == 'alphabetically-a-z') {
    [...list.children]
      .sort((a, b) => {
        const dataContentA = a.getAttribute('data-content');
        const dataContentB = b.getAttribute('data-content');

        const extractValue = (str, key) => {
          const match = str.match(new RegExp(`${key}=([^ ]+)`));
          return match ? match[1] : '';
        };

        const productA = extractValue(dataContentA, 'one-product');
        const productB = extractValue(dataContentB, 'one-product');

        return productA.localeCompare(productB);
      })
      .forEach(node => list.appendChild(node));

  } else if (sortType == 'alphabetically-z-a') {
    [...list.children]
      .sort((a, b) => {
        const dataContentA = a.getAttribute('data-content');
        const dataContentB = b.getAttribute('data-content');

        const extractValue = (str, key) => {
          const match = str.match(new RegExp(`${key}=([^ ]+)`));
          return match ? match[1] : '';
        };

        const productA = extractValue(dataContentA, 'one-product');
        const productB = extractValue(dataContentB, 'one-product');

        return productB.localeCompare(productA);
      })
      .forEach(node => list.appendChild(node));
  }
  else if (sortType == 'price-low-to-high') {
    [...list.children]
      .sort((a, b) => {
        const dataContentA = a.getAttribute('data-content');
        const dataContentB = b.getAttribute('data-content');

        const getPrice = str => {
          const match = str.match(/price=([\d.]+)/);
          return match ? parseFloat(match[1]) : 0;
        };

        const priceA = getPrice(dataContentA);
        const priceB = getPrice(dataContentB);

        return priceA - priceB;
      })
      .forEach(node => list.appendChild(node));
  } else if (sortType == 'price-high-to-low') {
    [...list.children]
      .sort((a, b) => {
        const dataContentA = a.getAttribute('data-content');
        const dataContentB = b.getAttribute('data-content');

        const getPrice = str => {
          const match = str.match(/price=([\d.]+)/);
          return match ? parseFloat(match[1]) : 0;
        };

        const priceA = getPrice(dataContentA);
        const priceB = getPrice(dataContentB);

        return priceB - priceA;
      })
      .forEach(node => list.appendChild(node));
  }
}

function filterProducts() {
  const checkboxes = document.querySelectorAll('.filter-checkbox');
  const selectedFilters = Array.from(checkboxes)
    .filter(checkbox => checkbox.checked)
    .map(checkbox => checkbox.value);
  const extractValue = (str, key) => {
    const match = str.match(new RegExp(`${key}=([^ ]+)`));
    return match ? match[1] : '';
  };
  const getPrice = str => {
    const match = str.match(/price=([\d.]+)/);
    return match ? parseFloat(match[1]) : 0;
  };

  const list = document.querySelector('#all-products');
  // const input = document.getElementById('search-name').value.toLowerCase();
  const input = document.getElementById('search-name').value.toLowerCase().replace(/\s+/g, '');

  [...list.children].forEach(product => {
    let shouldShow = true;
    const dataContent = product.getAttribute('data-content');

    // Category
    const category = extractValue(dataContent, 'category');
    if (!selectedFilters.includes(category)) {
      shouldShow = false;
    }

    // Gender
    const gender = extractValue(dataContent, 'gender');
    if (!selectedFilters.includes(gender)) {
      shouldShow = false;
    }

    //   // Price
    let priceRange = parseFloat(document.getElementById('priceRange').value) / 100 * 500;

    const price = getPrice(dataContent);
    if (price > priceRange) {
      shouldShow = false;
    }
    document.getElementById('select-price-range').textContent = priceRange;

    // Search
    const name = extractValue(dataContent, 'one-product').toLowerCase().replace(/\s+/g, '');
    if (!name.includes(input)) {
      shouldShow = false;
    }

    if (shouldShow) {
      product.style.display = '';
    } else {
      product.style.display = 'none';
    }
  });
}

function resetFilterOptions() {
  const checkboxes = document.querySelectorAll('.filter-checkbox');
  checkboxes.forEach(checkbox => {
    checkbox.checked = true;
  });

  document.getElementById('search-name').value = '';

  document.getElementById('priceRange').value = 100;
  document.getElementById('select-price-range').textContent = 500;

  filterProducts();
}

