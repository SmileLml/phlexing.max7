window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const story = info.row.data;
        const key   = story.type + '-' + story.grade;
        const html  = "<span class='label gray-pale rounded-xl clip'>" + storyGrades[key] + "</span> ";
        result.unshift({html});
    }

    if(info.col.name == 'roadmapOrPlan' && result)
    {
        const story      = info.row.data;
        const roadmapID  = story.roadmap;
        const planID     = story.plan.trim().replace(/^[,]+|[,]+$/g, '');
        const objectType = roadmapID != 0 ? 'roadmap' : (planID != 0 ? 'plan' : '');
        const objectID   = objectType == 'roadmap' ? roadmapID : (objectType == 'plan' ? planID : 0);

        let roadmapOrPlanName = '';
        if(objectType && objectID)
        {
            if(objectID.indexOf(',') !== -1)
            {
                const objectIdList = objectID.split(',');
                for(let key in objectIdList)
                {
                    let id = objectType + '-' + objectIdList[key];
                    roadmapOrPlanName += roadmapPlans[story.product][story.branch][id] + ', ';
                }
            }
            else
            {
                const key = objectType + '-' + objectID;
                roadmapOrPlanName = roadmapPlans[story.product][story.branch][key];
            }
        }

        result[0] = roadmapOrPlanName.trim().replace(/^[,]+|[,]+$/g, '');
        result[1]['attrs']['title'] = roadmapOrPlanName.trim().replace(/^[,]+|[,]+$/g, '');

        const labelName = objectType == 'roadmap' ? roadmapCommon : (objectType == 'plan' ? planCommon : '');
        const html      = roadmapOrPlanName ? "<span class='label gray-pale rounded-xl clip'>" + labelName + "</span> " : '';
        result.unshift({html});
    }

    return result;
}
