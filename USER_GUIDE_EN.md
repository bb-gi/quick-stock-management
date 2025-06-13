# User Guide - Quick Stock Management for WooCommerce

This guide will help you use the Quick Stock Management interface for WooCommerce, allowing you to quickly change the stock status of your products.

## Accessing the Quick Stock Management Page

1.  Log in to your WordPress admin dashboard.
2.  In the left-hand menu, hover over "Products".
3.  Click on "Quick Stock Management".

You will arrive at a page similar to this:

![Quick Stock Management Interface Screenshot](/home/ubuntu/qsm_interface_screenshot_placeholder.png)
*(Note: The image path above is a placeholder and might need adjustment if the image is stored within the plugin's assets folder, e.g., `../assets/images/qsm_interface_screenshot.png` or a similar relative path if the image is indeed part of the plugin package.)*

## Understanding the Interface

The interface is designed to be simple and intuitive:

*   **Product Name**: The first column displays the name of each product.
*   **Stock Status**: The second column contains a toggle switch and a visual badge indicating the current stock status.
    *   **Green switch (checked)**: The product is in stock (`instock`).
    *   **Gray switch (unchecked)**: The product is out of stock (`outofstock`).
    *   **Green "In Stock" badge**: Visual confirmation that the product is in stock.
    *   **Red "Out of Stock" badge**: Visual confirmation that the product is out of stock.

## Managing Product Stock

To change the stock status of a product:

1.  **Locate the product** you want to modify in the list.
2.  **Click the toggle switch** next to the product name.
    *   If the product is in stock, click to set it to out of stock.
    *   If the product is out of stock, click to set it to in stock.

### Visual Feedback

*   While the change is being saved, a small loading indicator (spinner) will appear next to the switch.
*   Once the update is successful, the status badge will change color and text to reflect the new status.

### Automatic Sorting

The product list automatically sorts to display in-stock products first, followed by out-of-stock products. Within each group, products are sorted alphabetically.

## Quick Troubleshooting

*   **Status not changing?** Check your internet connection. If the problem persists, reload the page.
*   **Error message?** Read the message to understand the issue. It might be a permissions problem or a server error.

If you have other questions or encounter problems, please consult the installation documentation or contact your system administrator.
