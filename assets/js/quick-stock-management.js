
/* quick-stock-management.js */

jQuery(document).ready(function($) {
    var $qsmProductList = $("#qsm-product-list");

    $qsmProductList.on("change", ".qsm-stock-toggle", function() {
        var $checkbox = $(this);
        var productId = $checkbox.data("product-id");
        var newStatus = $checkbox.is(":checked") ? "instock" : "outofstock";
        var $row = $checkbox.closest("tr");
        var $spinner = $row.find(".spinner");
        var $badge = $row.find(".qsm-stock-badge");
        var originalStatus = $checkbox.is(":checked") ? "outofstock" : "instock"; // If checked now, it was outofstock before

        // Show spinner and disable checkbox
        $row.addClass("saving");
        $checkbox.prop("disabled", true);

        $.ajax({
            url: qsm_ajax_object.ajax_url,
            type: "POST",
            data: {
                action: "update_quick_stock_status",
                product_id: productId,
                stock_status: newStatus,
                nonce: qsm_ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update badge text and class
                    $badge.text(newStatus === "instock" ? "In Stock" : "Out of Stock");
                    $badge.removeClass("qsm-badge-instock qsm-badge-outofstock").addClass("qsm-badge-" + newStatus);

                    // Move row based on new status for sorting
                    var $tbody = $row.parent();
                    var $instockRows = $tbody.children().filter(function() {
                        return $(this).find(".qsm-stock-toggle").is(":checked");
                    });
                    var $outofstockRows = $tbody.children().filter(function() {
                        return !$(this).find(".qsm-stock-toggle").is(":checked");
                    });

                    // Re-sort within each group (alphabetical)
                    var sortedInstock = $instockRows.get().sort(function(a, b) {
                        return $(a).find(".product-name strong").text().localeCompare($(b).find(".product-name strong").text());
                    });
                    var sortedOutofstock = $outofstockRows.get().sort(function(a, b) {
                        return $(a).find(".product-name strong").text().localeCompare($(b).find(".product-name strong").text());
                    });

                    $tbody.empty().append(sortedInstock).append(sortedOutofstock);

                    // Optional: Display a subtle confirmation
                    // console.log(response.data.message);
                } else {
                    // Rollback visual state on error
                    $checkbox.prop("checked", originalStatus === "instock");
                    alert("Error: " + response.data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Rollback visual state on error
                $checkbox.prop("checked", originalStatus === "instock");
                alert("AJAX Error: " + textStatus + " - " + errorThrown);
            },
            complete: function() {
                // Hide spinner and re-enable checkbox
                $row.removeClass("saving");
                $checkbox.prop("disabled", false);
            }
        });
    });
});


