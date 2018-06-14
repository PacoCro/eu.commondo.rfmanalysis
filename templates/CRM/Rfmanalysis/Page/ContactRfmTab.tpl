
<div class="rfm">
    <h2>{$title}</h2>
    <h3>RFM Group: <span class="rfm-group-{$groupId}">{$group}</span></h3>
    <div class="rfm-grid-container">
        <div class="rfm-grid-item">Recency:</div>
        <div class="rfm-grid-item">Frequency:</div>
        <div class="rfm-grid-item">Monetary value:</div>
        <div class="rfm-grid-item">{$rfmR}/5</div>
        <div class="rfm-grid-item">{$rfmF}/5</div>
        <div class="rfm-grid-item">{$rfmM}/5</div>
    </div>
    <h2>Category map</h2>

    <div class="rfm-map-grid-container">
        <div class="rfm-map-grid-item-1"><span {if $groupId == 1}style="border: 1px solid black; font-weight: 800;"{/if}>Can't lose them</span></div>
        <div class="rfm-map-grid-item-2"><span {if $groupId == 2}style="border: 1px solid black; font-weight: 800;"{/if}>At risk of loosing</span></div>
        <div class="rfm-map-grid-item-22"><span></span></div>
        <div class="rfm-map-grid-item-3"><span {if $groupId == 3}style="border: 1px solid black; font-weight: 800;"{/if}>Loyal customers</span></div>
        <div class="rfm-map-grid-item-33"></div>
        <div class="rfm-map-grid-item-4"><span {if $groupId == 4}style="border: 1px solid black; font-weight: 800;"{/if}>Hibernating</span></div>
        <div class="rfm-map-grid-item-5"><span {if $groupId == 5}style="border: 1px solid black; font-weight: 800;"{/if}>Lost</span></div>
        <div class="rfm-map-grid-item-55"></div>
        <div class="rfm-map-grid-item-6"><span {if $groupId == 6}style="border: 1px solid black; font-weight: 800;"{/if}>Need attention</span></div>
        <div class="rfm-map-grid-item-7"><span {if $groupId == 7}style="border: 1px solid black; font-weight: 800;"{/if}>About to sleep</span></div>
        <div class="rfm-map-grid-item-8"><span {if $groupId == 8}style="border: 1px solid black; font-weight: 800;"{/if}>Promising</span></div>
        <div class="rfm-map-grid-item-9"><span {if $groupId == 9}style="border: 1px solid black; font-weight: 800;"{/if}>Potential loyalist</span></div>
        <div class="rfm-map-grid-item-10"><span {if $groupId == 10}style="border: 1px solid black; font-weight: 800;"{/if}>New customers</span></div>
        <div class="rfm-map-grid-item-11"><span {if $groupId == 11}style="border: 1px solid black; font-weight: 800;"{/if}>Champions</span></div>
    </div>
</div>
