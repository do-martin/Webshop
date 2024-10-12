export default class Cart {
  constructor(item_number, product_name, price, amount, item_inventory, category, path, gender) {
    this.item_number = parseInt(item_number);
    this.product_name = product_name;
    this.price = parseFloat(price).toFixed(2);
    this.amount = parseInt(amount);
    this.item_inventory = parseInt(item_inventory);
    this.category = category;
    this.path = path.replace("pictureNumber", "1");
    this.totalAmount = parseFloat(this.calculateTotalAmount()).toFixed(2);
    this.totalAmountAfterSale = parseFloat(this.calculateTotalAmountAfterSale()).toFixed(2);
    this.gender = gender;
  }

  calculateTotalAmount() {
    return (this.price * this.amount).toFixed(2);
  }

  calculateTotalAmountAfterSale() {
    let totalAmountAfterSale = 0;
    if (this.amount >= 10) {
      let discount = 0.2;
      totalAmountAfterSale = totalAmountAfterSale + this.amount * this.price * (1 - discount);
    } else if (this.amount >= 5) {
      let discount = 0.1;
      totalAmountAfterSale = totalAmountAfterSale + this.amount * this.price * (1 - discount);
    } else {
      totalAmountAfterSale = totalAmountAfterSale + this.amount * this.price;
    }
    return totalAmountAfterSale.toFixed(2);
  }

  getItemNumber() {
    return this.item_number;
  }
  getProductName() {
    return this.product_name;
  }
  getPrice() {
    return this.price;
  }
  getAmount() {
    return this.amount;
  }
  getItemInventory() {
    return this.item_inventory;
  }
  getCategory() {
    return this.category;
  }
  getPath() {
    return this.path;
  }
  getTotalAmount() {
    return this.totalAmount;
  }
  setTotalAmount(totalAmount) {
    this.totalAmount = totalAmount;
  }
  getTotalAmountAfterSale() {
    return this.totalAmountAfterSale;
  }
  getGender() {
    return this.gender;
  }
}
