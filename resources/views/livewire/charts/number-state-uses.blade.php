<div>
    <label title="The State counts our numbers with a 7 day lag ({{ $laggedDate  }}) from the To date ( it turns out there is an additional {{ $secondaryLagDays }} days added to the expected lag). This is based on the rules they have posted on determining current county numbers. However their numbers seldom match the rules given." for="seven-day-average" class="text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4">Expected <a href="https://covid19.ca.gov/safer-economy/"><img src="https://covid19.ca.gov/img/icons/favicon-16x16.png" style="display: inline"></a> count:</label>
    <input type="text" wire:model="laggedCount" id="lagged-count" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-grey focus:border-purple-500" readonly>
</div>
